<?php

namespace App\Controller\Api\admin\secure;

use App\Entity\CodePromo;
use App\Entity\Logement;
use App\Repository\CategorieDestinationRepository;
use App\Repository\CategorieHebergementRepository;
use App\Repository\CategorieInspirationRepository;
use App\Repository\CodePromoRepository;
use App\Repository\LogementRepository;
use App\Repository\UserRepository;
use App\Repository\OptionRepository;
use App\Service\FileUploaderInsolite;
use App\Utils\ErrorHttp;
use App\Utils\Globals;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/admins/secure')]
class LogementController extends AbstractController
{

    private Globals $globals;
    private LogementRepository $logementRepository;

    private CodePromoRepository $codePromoRepository;

    private CategorieDestinationRepository $destinationRepository;

    private CategorieHebergementRepository $hebergementRepository;

    private CategorieInspirationRepository $inspirationRepository;

    private UserRepository $userRepository;

    private FileUploaderInsolite $fileUploaderInsolite;

    private OptionRepository $optionRepository;

    public function __construct(Globals $globals, LogementRepository $logementRepository, CodePromoRepository $codePromoRepository,CategorieDestinationRepository $destinationRepository,CategorieHebergementRepository $hebergementRepository,CategorieInspirationRepository $inspirationRepository,UserRepository $userRepository, FileUploaderInsolite $fileUploaderInsolite, OptionRepository $optionRepository)
    {
        $this->globals = $globals;
        $this->logementRepository = $logementRepository;
        $this->codePromoRepository = $codePromoRepository;
        $this->destinationRepository = $destinationRepository;
        $this->hebergementRepository = $hebergementRepository;
        $this->inspirationRepository = $inspirationRepository;
        $this->userRepository = $userRepository;
        $this->fileUploaderInsolite = $fileUploaderInsolite;
        $this->optionRepository = $optionRepository;
    }

    #[Route('/logementsAdmin/{id}', name: 'logementAllAdmin', methods: 'GET')]
    public function logementAllAdmin(int $id): JsonResponse
    {
        return $this->globals->getData(array_map(function (Logement $logement){
            return $logement->toArray();
        },$this->logementRepository->findBy(['fk_user'=>$id])));
    }

    #[Route('/logementOneByIdAdmin/{id}', name: 'oneByIdLogementAdmin', methods: 'GET')]
    public function oneByIdLogementAdmin(int $id): JsonResponse
    {
        $logement = $this->logementRepository->findOneBy(['id'=>$id]);
        //printf($user);
        return $this->globals->getOnData($logement?->toArray());
    }

    #[Route('/save-logementAdmin', name: 'saveLogementAdmin', methods: 'POST')]
    public function saveLogementAdmin(): JsonResponse
    {
        $data = $this->globals->jsondecode();

        //Verification des inputs reçu
        if (!isset($data->libelle,$data->montant,$data->description,$data->capaciteAccueil,$data->disponible,$data->nombrePiece,$data->nombreChambre,$data->categorie_hebergement,$data->categorie_destination,$data->categorie_inspiration,$data->userId,$data->enableCodepromo,$data->pourcentage))
            return $this->globals->error(ErrorHttp::FORM_ERROR);

        $logementExist = $this->logementRepository->findOneBy(['libelle'=>$data->libelle]);

        if ($logementExist)
            return $this->globals->error([
                "message" => "Ce logement existe déja",
                'codeHttp'=> 200
            ]);

        $destination = $this->destinationRepository->findOneBy(['id'=>$data->categorie_destination]);
        $hebergement = $this->hebergementRepository->findOneBy(['id'=>$data->categorie_hebergement]);
        $inspiration = $this->inspirationRepository->findOneBy(['id'=>$data->categorie_inspiration]);
        $user = $this->userRepository->findOneBy(['id'=>$data->userId]);

        $logement = new Logement();

        //Enregistrement
        $logement->setLibelle($data->libelle)
            ->setMontant($data->montant)
            ->setDescription($data->description)
            ->setCapaciteAccueil($data->capaciteAccueil)
            ->setDisponible($data->disponible)
            ->setNombrePiece($data->nombrePiece)
            ->setNombreChambre($data->nombreChambre)
            ->setFkCategorieDestination($destination)
            ->setFkCategorieInspiration($inspiration)
            ->setFkCategorieHebergement($hebergement)
            ->setHebergementId($data->categorie_hebergement)
            ->setDestinationId($data->categorie_destination)
            ->setInspirationId($data->categorie_inspiration)
            ->setUserId($data->userId)
            ->setFkUser($user)
            ->setCreated($this->globals->dateNow());

        $this->logementRepository->save($logement,true);

        if ($data->enableCodepromo == true)
        {
            $codepromo = new CodePromo();

            $codepromo->setLibelle("SE455")
                ->setPourcentage($data->pourcentage)
                ->setFkLogement($logement);

            $this->codePromoRepository->save($codepromo,true);

            return $this->globals->success([$data->userId], "Le logement à été créer avec success");
        }

        return $this->globals->success([$data->userId], "Le logement à été créer avec success");
    }

    #[Route('/logementUpdatedAdmin/{id}', name: 'updatedLogementByIdAdmin', methods: 'PUT')]
    public function updatedLogementByIdAdmin(int $id): JsonResponse
    {
        $data = $this->globals->jsondecode();
        $logement = $this->logementRepository->findOneBy(['id'=>$id]);

        //Verification des inputs reçu
        if (!isset($data->libelle,$data->montant,$data->description,$data->capaciteAccueil,$data->nombrePiece,$data->nombreChambre,$data->categorie_hebergement,$data->categorie_destination,$data->categorie_inspiration))
            return $this->globals->error(ErrorHttp::FORM_ERROR);

        if (!$logement)
            return $this->globals->error(ErrorHttp::ID_ERROR);

        $destination = $this->destinationRepository->findOneBy(['id'=>$data->categorie_destination]);
        $hebergement = $this->hebergementRepository->findOneBy(['id'=>$data->categorie_hebergement]);
        $inspiration = $this->inspirationRepository->findOneBy(['id'=>$data->categorie_inspiration]);

        $logement->setLibelle($data->libelle)
            ->setMontant($data->montant)
            ->setDescription($data->description)
            ->setCapaciteAccueil($data->capaciteAccueil)
            ->setNombrePiece($data->nombrePiece)
            ->setNombreChambre($data->nombreChambre)
            ->setFkCategorieDestination($destination)
            ->setFkCategorieInspiration($inspiration)
            ->setFkCategorieHebergement($hebergement)
            ->setHebergementId($data->categorie_hebergement)
            ->setDestinationId($data->categorie_destination)
            ->setInspirationId($data->categorie_inspiration);

        $this->logementRepository->updated(true);

        // if ($data->enableCodepromo == true)
        // {
        //     $codepromo = $this->codePromoRepository->findOneBy(['id'=>$id]);

        //     $codepromo->setLibelle("S23K55")
        //         ->setPourcentage($data->pourcentage);

        //     $this->codePromoRepository->updated(true);

        //     return $this->globals->success(null,"Le logement à été mise a jour");
        // }
        //printf($user);
        return $this->globals->success(null,"Le logement à été mise a jour");
    }

    #[Route('/updatedImageLogementAdmin', name: 'logementUpdatedImageByIdAdmin', methods: 'POST')]
    public function logementUpdatedImageByIdAdmin(Request $request): JsonResponse
    {
        $uploadedFile = $request->files->get('file');
        $id = $request->get('id');
        $typeImg = $request->get('typeImg');
        // $data = $this->globals->jsondecode();
        $logement = $this->logementRepository->findOneBy(['id'=> $id]);

        if (!$logement)
            return $this->globals->error(ErrorHttp::ID_ERROR);

        if($typeImg == 'image1')
        {
            $image = $this->fileUploaderInsolite->upload($uploadedFile); 

            $logement->setImage1($image)
                ->setDisponible("1")
                ->setStatut("1");

            $this->logementRepository->updated(true);

            return $this->globals->success(null,"La banniere à été mise a jour");
        }

        if($typeImg == 'image2')
        {
            $image = $this->fileUploaderInsolite->upload($uploadedFile); 

            $logement->setImage2($image);

            $this->logementRepository->updated(true);

            return $this->globals->success(null,"La banniere à été mise a jour");
        }

        if($typeImg == 'image3')
        {
            $image = $this->fileUploaderInsolite->upload($uploadedFile); 

            $logement->setImage3($image);

            $this->logementRepository->updated(true);

            return $this->globals->success(null,"La banniere à été mise a jour");
        }

        $image = $this->fileUploaderInsolite->upload($uploadedFile); 

        $logement->setImage4($image);

        $this->logementRepository->updated(true);

        return $this->globals->success(null,"La banniere à été mise a jour");


    }

    #[Route('/activeLogementAdmin', name: 'logementActiveByIdAdmin', methods: 'POST')]
    public function logementActiveByIdAdmin(): JsonResponse
    {
        $data = $this->globals->jsondecode();
        $logement = $this->logementRepository->findOneBy(['id'=> $data->id]);

        //Verification des inputs reçu
        if (!isset($data->statut, $data->id))
            return $this->globals->error(ErrorHttp::FORM_ERROR);

        if (!$logement)
            return $this->globals->error(ErrorHttp::ID_ERROR);


        $logement->setStatut($data->statut);

        $this->logementRepository->updated(true);

        return $this->globals->success(null, "Le logement à été confirmer");


    }


    #[Route('/detail-logementAdmin/{id}', name: 'logementDetailAdmin', methods: 'GET')]
    public function logementDetailAdmin(int $id): JsonResponse
    {

        $logement = $this->logementRepository->findOneBy(['id'=>$id]);
        $destination = $this->destinationRepository->findOneBy(['id'=>$logement->getDestinationId()]);
        $hebergement = $this->hebergementRepository->findOneBy(['id'=>$logement->getHebergementId()]);
        $inspiration = $this->inspirationRepository->findOneBy(['id'=>$logement->getInspirationId()]);
        $user = $this->userRepository->findOneBy(['id'=>$logement->getUserId()]);
        //printf($user);
        return $this->globals->getData([
          "logement" => $logement?->toArray(),
          "destination" => $destination?->toArray(),
          "hebergement" => $hebergement?->toArray(),
          "inspiration" => $inspiration?->toArray(),
          "user" => $user?->toArray()
        ]);
    }


    #[Route('/delete-logementAdmin/{id}', name: 'logementDeleteAdmin', methods: 'DELETE')]
    public function logementDeleteAdmin(int $id): JsonResponse
    {

        $logement = $this->logementRepository->findOneBy(['id'=>$id]);

        if (!$logement)
            return $this->globals->error(ErrorHttp::ID_DELETE_ERROR);
        
        $codepromo = $this->codePromoRepository->findOneBy(['fk_logement'=>$id]);
        $option = $this->optionRepository->findOneBy(['logement'=>$id]);

        if ($option){
            return $this->globals->error([
                "message" => "Vous ne pouvez pas supprimer cette insolite, supprimer d'abord ces options",
                'codeHttp'=> 200
            ]);
        }

        if ($codepromo){
            // $option= $this->optionRepository->findBy(['logement'=>$id]);
            // $this->optionRepository->remove($option, true);
            $this->codePromoRepository->remove($codepromo, true);
            $this->logementRepository->remove($logement, true);
            return $this->globals->success(null,"Le logement à été supprimer avec success");
        }    
        
        
        // $this->logementRepository->removeFkOption();
        //$logement->removeFkOption($option);
        $this->logementRepository->remove($logement, true);
        //printf($user);
        return $this->globals->success(null,"Le logement à été supprimer avec success");
    }


}