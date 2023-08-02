<?php

namespace App\Controller\Api\admin\secure;

use App\Entity\Option;
use App\Repository\OptionRepository;
use App\Repository\LogementRepository;
use App\Utils\ErrorHttp;
use App\Utils\Globals;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/admins/secure')]
class OptionController extends AbstractController
{
    private Globals $globals;
    private OptionRepository $optionRepository;
    private LogementRepository $logementRepository;

    public function __construct(Globals $globals, OptionRepository $optionRepository, LogementRepository $logementRepository)
    {
        $this->globals = $globals;
        $this->optionRepository = $optionRepository;
        $this->logementRepository = $logementRepository;
    }

    #[Route('/optionsAllAdmin', name: 'optionAllAdmin', methods: 'GET')]
    public function optionAllAdmin(): JsonResponse
    {
        return $this->globals->getData(array_map(function (Option $option){
            return $option->toArray();
        },$this->optionRepository->findAll()));
    }

    #[Route('/optionsAllByIdAdmin/{id}', name: 'allByIdOptionsAdmin', methods: 'GET')]
    public function allByIdOptionsAdmin(int $id): JsonResponse
    {
        return $this->globals->getData(array_map(function (Option $option){
            return $option->toArray();
        },$this->optionRepository->findBy(['logement'=>$id])));
        // $option = $this->optionRepository->findOneBy(['id'=>$id]);
        // //printf($user);
        // return $this->globals->getData($option?->toArray());
    }

    #[Route('/optionOneById/{id}', name: 'oneByIdOption', methods: 'GET')]
    public function oneByIdOption(int $id): JsonResponse
    {
        $option = $this->optionRepository->findOneBy(['id'=>$id]);
        //printf($user);
        return $this->globals->getOnData($option?->toArray());
    }

    #[Route('/save-optionAdmin', name: 'saveOptionAdmin', methods: 'POST')]
    public function saveOptionAdmin(): JsonResponse
    {
        $data = $this->globals->jsondecode();

        //Verification des inputs reçu
        if (!isset($data->libelle,$data->logement_id))
            return $this->globals->error(ErrorHttp::FORM_ERROR);

        $optionExist = $this->optionRepository->findOneBy(['libelle'=>$data->libelle]);
        $logement = $this->logementRepository->findOneBy(['id'=>$data->logement_id]);

        if ($optionExist)
            return $this->globals->error([
                "message" => "Cet option existe déja",
                'codeHttp'=> 200
            ]);

        $option = new Option();

        //Enregistrement
        $option->setLibelle($data->libelle)
            ->setLogement($logement);

        $this->optionRepository->save($option,true);

        return $this->globals->success(null, "L'option à été créer avec success");
    }

    #[Route('/optionUpdatedAdmin/{id}', name: 'updatedOptionByIdAdmin', methods: 'PUT')]
    public function updatedOptionByIdAdmin(int $id): JsonResponse
    {
        $data = $this->globals->jsondecode();
        $option = $this->optionRepository->findOneBy(['id'=>$id]);

        //Verification des inputs reçu
        if (!isset($data->libelle))
            return $this->globals->error(ErrorHttp::FORM_ERROR);

        if (!$option)
            return $this->globals->error(ErrorHttp::ID_ERROR);

        $option->setLibelle($data->libelle);

        $this->optionRepository->updated(true);
        //printf($user);
        return $this->globals->success(null, "Mise à jour à été effectuer avec success");
    }

    #[Route('/delete-optionAdmin/{id}', name: 'optionDeleteAdmin', methods: 'DELETE')]
    public function optionDeleteAdmin(int $id): JsonResponse
    {

        $option = $this->optionRepository->findOneBy(['id'=>$id]);

        if (!$option)
            return $this->globals->error(ErrorHttp::ID_DELETE_ERROR);        

        $this->optionRepository->remove($option, true);
        //printf($user);
        return $this->globals->success(null,"L'option à été supprimer avec success");
    }

}