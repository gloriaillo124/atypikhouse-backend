<?php

namespace App\Controller\Api\website;

use App\Entity\CategorieDestination;
use App\Entity\CategorieHebergement;
use App\Entity\CategorieInspiration;
use App\Entity\Logement;
use App\Entity\Option;
use App\Repository\OptionRepository;
use App\Repository\CategorieDestinationRepository;
use App\Repository\CategorieHebergementRepository;
use App\Repository\CategorieInspirationRepository;
use App\Repository\CodePromoRepository;
use App\Repository\LogementRepository;
use App\Repository\UserRepository;
use App\Utils\ErrorHttp;
use App\Utils\Globals;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/website')]
class WebsiteController extends AbstractController
{

    private Globals $globals;
    private LogementRepository $logementRepository;

    private CodePromoRepository $codePromoRepository;

    private CategorieDestinationRepository $destinationRepository;

    private CategorieHebergementRepository $hebergementRepository;

    private CategorieInspirationRepository $inspirationRepository;

    private UserRepository $userRepository;

    private OptionRepository $optionRepository;

    public function __construct(Globals $globals, LogementRepository $logementRepository, CodePromoRepository $codePromoRepository,CategorieDestinationRepository $destinationRepository,CategorieHebergementRepository $hebergementRepository,CategorieInspirationRepository $inspirationRepository,UserRepository $userRepository, OptionRepository $optionRepository)
    {
        $this->globals = $globals;
        $this->logementRepository = $logementRepository;
        $this->codePromoRepository = $codePromoRepository;
        $this->destinationRepository = $destinationRepository;
        $this->hebergementRepository = $hebergementRepository;
        $this->inspirationRepository = $inspirationRepository;
        $this->userRepository = $userRepository;
        $this->optionRepository = $optionRepository;
    }

    #[Route('/logements', name: 'logementAll', methods: 'GET')]
    public function logementAll(): JsonResponse
    {
        return $this->globals->getData(array_map(function (Logement $logement){
            return $logement->toArray();
        },$this->logementRepository->findAll()));
    }

    #[Route('/logementOneByIdWeb/{id}', name: 'oneByIdLogementWeb', methods: 'GET')]
    public function oneByIdLogementWeb(int $id): JsonResponse
    {
        $logement = $this->logementRepository->findOneBy(['id'=>$id]);
        //printf($user);
        return $this->globals->getOnData($logement?->toArray());
    }

    #[Route('/logementRechercheByIdCatByIdHeb', name: 'logementRechercheBy', methods: 'POST')]
    public function logementRechercheBy(): JsonResponse
    {
        $data = $this->globals->jsondecode();

        //Verification des inputs reçu
        if (!isset($data->destinationId, $data->hebergementId, $data->nbPersonne))
            return $this->globals->error(ErrorHttp::FORM_ERROR);

        $logement = $this->logementRepository->findOneBy(['fk_categorie_destination'=>$data->destinationId, 'fk_categorie_hebergement'=>$data->hebergementId, 'capaciteAccueil'=>$data->nbPersonne]);
       
        return $this->globals->success(
            $logement?->toArray()
        );
    }

    #[Route('/logementAllLimites', name: 'logementAllLimite', methods: 'GET')]
    public function logementAllLimite(): JsonResponse
    {
        //'statut'=> '1'
        return $this->globals->getData(array_map(function (Logement $logement){
            return $logement->toArray();
        },$this->logementRepository->findBy(['statut'=> '1'],['libelle'=> 'DESC'],limit:6)));
    }

    #[Route('/optionsAllByISite/{id}', name: 'allByIdOptionsSite', methods: 'GET')]
    public function allByIdOptionsSite(int $id): JsonResponse
    {
        return $this->globals->getData(array_map(function (Option $option){
            return $option->toArray();
        },$this->optionRepository->findBy(['logement'=>$id])));
    }

    #[Route('/categorieinspiration', name: 'inspirationAll', methods: 'GET')]
    public function inspirationAll(): JsonResponse
    {
        return $this->globals->getData(array_map(function (CategorieInspiration $inspiration){
            return $inspiration->toArray();
        },$this->inspirationRepository->findAll()));
    }

    #[Route('/categorieinspirationLimite', name: 'inspirationAllLimite', methods: 'GET')]
    public function inspirationAllLimite(): JsonResponse
    {
        return $this->globals->getData(array_map(function (CategorieInspiration $inspiration){
            return $inspiration->toArray();
        },$this->inspirationRepository->findBy([],['libelle'=> 'DESC'],limit:6)));
    }

    #[Route('/categoriehebergement', name: 'hebergementAll', methods: 'GET')]
    public function hebergementAll(): JsonResponse
    {
        return $this->globals->getData(array_map(function (CategorieHebergement $hebergement){
            return $hebergement->toArray();
        },$this->hebergementRepository->findAll()));
    }

    #[Route('/categoriehebergementLimit', name: 'hebergementAllLimite', methods: 'GET')]
    public function hebergementAllLimite(): JsonResponse
    {
        return $this->globals->getData(array_map(function (CategorieHebergement $hebergement){
            return $hebergement->toArray();
        },$this->hebergementRepository->findBy([],['libelle'=> 'DESC'],limit:6)));
    }

    #[Route('/categoriedestination', name: 'destinationAll', methods: 'GET')]
    public function destinationAll(): JsonResponse
    {
        return $this->globals->getData(array_map(function (CategorieDestination $destination){
            return $destination->toArray();
        },$this->destinationRepository->findAll()));
    }

    #[Route('/categoriedestinationLimite', name: 'destinationAllLimite', methods: 'GET')]
    public function destinationAllLimite(): JsonResponse
    {
        return $this->globals->getData(array_map(function (CategorieDestination $destination){
            return $destination->toArray();
        },$this->destinationRepository->findBy([],['libelle'=> 'DESC'],limit:6)));
    }

    #[Route('/logementByDestinationId/{id}', name: 'logementByIdDestination', methods: 'GET')]
    public function logementByIdDestination(int $id): JsonResponse
    {
        $logements = $this->logementRepository->findBy(['statut'=> '1','fk_categorie_destination'=>$id]);
        //printf($user);
        return $this->globals->getData(array_map(function (Logement $logement){
            return $logement->toArray();
        },$logements));
    }

    #[Route('/logementByHebergementId/{id}', name: 'hebergementByIdDestination', methods: 'GET')]
    public function hebergementByIdDestination(int $id): JsonResponse
    {
        $logements = $this->logementRepository->findBy(['statut'=> '1','fk_categorie_hebergement'=>$id]);
        //printf($user);
        return $this->globals->getData(array_map(function (Logement $logement){
            return $logement->toArray();
        },$logements));
    }

    #[Route('/logementByInspirationId/{id}', name: 'inspirationByIdDestination', methods: 'GET')]
    public function inspirationByIdDestination(int $id): JsonResponse
    {
        $logements = $this->logementRepository->findBy(['statut'=> '1','fk_categorie_inspiration'=>$id]);
        //printf($user);
        return $this->globals->getData(array_map(function (Logement $logement){
            return $logement->toArray();
        },$logements));
    }
}