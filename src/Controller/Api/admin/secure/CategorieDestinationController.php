<?php

namespace App\Controller\Api\admin\secure;

use App\Entity\CategorieDestination;
use App\Repository\CategorieDestinationRepository;
use App\Utils\ErrorHttp;
use App\Utils\Globals;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
#[Route('api/admins/secure')]
class CategorieDestinationController extends AbstractController
{


    private Globals $globals;
    private CategorieDestinationRepository $destinationRepository;

    public function __construct(Globals $globals, CategorieDestinationRepository $destinationRepository)
    {
        $this->globals = $globals;
        $this->destinationRepository = $destinationRepository;
    }

    #[Route('/categoriedestinationAdmin', name: 'destinationAllAdmin', methods: 'GET')]
    public function destinationAllAdmin(): JsonResponse
    {
        return $this->globals->getData(array_map(function (CategorieDestination $destination){
            return $destination->toArray();
        },$this->destinationRepository->findAll()));
    }

    #[Route('/destinationOneByIdAdmin/{id}', name: 'oneByIdDestination', methods: 'GET')]
    public function oneByIdDestination(int $id): JsonResponse
    {
        $destination = $this->destinationRepository->findOneBy(['id'=>$id]);
        //printf($user);
        return $this->globals->getOnData($destination?->toArray());
    }

    #[Route('/save-categoriedestinationAdmin', name: 'saveDestination', methods: 'POST')]
    public function saveDestination(): JsonResponse
    {
        $data = $this->globals->jsondecode();

        //Verification des inputs reçu
        if (!isset($data->libelle))
            return $this->globals->error(ErrorHttp::FORM_ERROR);

        $destinationExist = $this->destinationRepository->findOneBy(['libelle'=>$data->libelle]);

        if ($destinationExist)
            return $this->globals->error([
                "message" => "Cet catégorie destination existe déja",
                'codeHttp'=> 200
            ]);

        $destination = new CategorieDestination();

        //Enregistrement
        $destination->setLibelle($data->libelle);

        $this->destinationRepository->save($destination,true);

        return $this->globals->success([],"La catégorie destination à été créer avec success");
    }

    #[Route('/categoriedestinationUpdatedAdmin/{id}', name: 'updatedDestinationById', methods: 'PUT')]
    public function updatedDestinationById(int $id): JsonResponse
    {
        $data = $this->globals->jsondecode();
        $destination = $this->destinationRepository->findOneBy(['id'=>$id]);

        //Verification des inputs reçu
        if (!isset($data->libelle))
            return $this->globals->error(ErrorHttp::FORM_ERROR);

        if (!$destination)
            return $this->globals->error(ErrorHttp::ID_ERROR);

        $destination->setLibelle($data->libelle);

        $this->destinationRepository->updated(true);
        //printf($user);
        return $this->globals->success(null,"La categorie à été mise a jour");
    }

    #[Route('/delete-categoriedestinationAdmin/{id}', name: 'destinationDelete', methods: 'DELETE')]
    public function destinationDelete(int $id): JsonResponse
    {

        $destination = $this->destinationRepository->findOneBy(['id'=>$id]);

        if (!$destination)
            return $this->globals->error(ErrorHttp::ID_DELETE_ERROR);

        $this->destinationRepository->remove($destination, true);
        //printf($user);
        return $this->globals->success(null,"La catégorie destination à été supprimer avec success");
    }

}