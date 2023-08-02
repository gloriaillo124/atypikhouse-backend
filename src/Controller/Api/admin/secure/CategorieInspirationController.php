<?php

namespace App\Controller\Api\admin\secure;

use App\Entity\CategorieInspiration;
use App\Repository\CategorieInspirationRepository;
use App\Utils\ErrorHttp;
use App\Utils\Globals;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
#[Route('api/admins/secure')]
class CategorieInspirationController extends AbstractController
{


    private Globals $globals;
    private CategorieInspirationRepository $inspirationRepository;

    public function __construct(Globals $globals,CategorieInspirationRepository $inspirationRepository)
    {
        $this->globals = $globals;
        $this->inspirationRepository = $inspirationRepository;
    }

    #[Route('/categorieinspirationAdmin', name: 'inspirationAllAdmin', methods: 'GET')]
    public function inspirationAllAdmin(): JsonResponse
    {
        return $this->globals->getData(array_map(function (CategorieInspiration $inspiration){
            return $inspiration->toArray();
        },$this->inspirationRepository->findAll()));
    }

    #[Route('/inspirationOneByIdAdmin/{id}', name: 'oneByIdInspirationAdmin', methods: 'GET')]
    public function oneByIdInspirationAdmin(int $id): JsonResponse
    {
        $inspiration = $this->inspirationRepository->findOneBy(['id'=>$id]);
        //printf($user);
        return $this->globals->getOnData($inspiration?->toArray());
    }

    #[Route('/save-categorieinspirationAdmin', name: 'saveInspirationAdmin', methods: 'POST')]
    public function saveInspirationAdmin(): JsonResponse
    {
        $data = $this->globals->jsondecode();

        //Verification des inputs reçu
        if (!isset($data->libelle))
            return $this->globals->error(ErrorHttp::FORM_ERROR);

        $inspirationExist = $this->inspirationRepository->findOneBy(['libelle'=>$data->libelle]);

        if ($inspirationExist)
            return $this->globals->error([
                "message" => "Cet catégorie d'inspiration existe déja",
                'codeHttp'=> 200
            ]);

        $inspiration = new CategorieInspiration();

        //Enregistrement
        $inspiration->setLibelle($data->libelle);

        $this->inspirationRepository->save($inspiration,true);

        return $this->globals->success(null,"La catégorie inspiration à été créer avec success");
    }

    #[Route('/categorieinspirationUpdatedAdmin/{id}', name: 'updatedInspirationByIdAdmin', methods: 'PUT')]
    public function updatedInspirationByIdAdmin(int $id): JsonResponse
    {
        $data = $this->globals->jsondecode();
        $inspiration = $this->inspirationRepository->findOneBy(['id'=>$id]);

        //Verification des inputs reçu
        if (!isset($data->libelle))
            return $this->globals->error(ErrorHttp::FORM_ERROR);

        if (!$inspiration)
            return $this->globals->error(ErrorHttp::ID_ERROR);

        $inspiration->setLibelle($data->libelle);

        $this->inspirationRepository->updated(true);
        //printf($user);
        return $this->globals->success(null,"La categorie à été mise a jour");
    }

    #[Route('/delete-categorieinspirationAdmin/{id}', name: 'inspirationDeleteAdmin', methods: 'DELETE')]
    public function inspirationDeleteAdmin(int $id): JsonResponse
    {

        $inspiration = $this->inspirationRepository->findOneBy(['id'=>$id]);

        if (!$inspiration)
            return $this->globals->error(ErrorHttp::ID_DELETE_ERROR);

        $this->inspirationRepository->remove($inspiration, true);
        //printf($user);
        return $this->globals->success(null,"La catégorie d'inspiration à été supprimer avec success");
    }
}