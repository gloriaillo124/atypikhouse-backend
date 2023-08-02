<?php

namespace App\Controller\Api\admin\secure;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use App\Utils\ErrorHttp;
use App\Utils\Globals;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/admins/secure')]
class UserController extends AbstractController
{
    private Globals $globals;
    private UserRepository $userRepository;
    private FileUploader $fileUploader;

    public function __construct(Globals $globals, UserRepository $userRepository,FileUploader $fileUploader)
    {
        $this->globals = $globals;
        $this->userRepository = $userRepository;
        $this->fileUploader = $fileUploader;
        
    }

    #[Route('/users', name: 'usersAll', methods: 'GET')]
    public function usersAll(): JsonResponse
    {
         return $this->globals->getData(array_map(function (User $user){
             return $user->toArray();
        },$this->userRepository->findAll()));
    }

    #[Route('/userOneByIdAdmin/{id}', name: 'userOneByIdAdmin', methods: 'GET')]
    public function userOneByIdAdmin(int $id): JsonResponse
    {
        $user = $this->userRepository->findOneBy(['id'=>$id]);
        //printf($user);
        return $this->globals->getOnData($user?->toArray());
    }

    #[Route('/userUpdatedByIdAdmin/{id}', name: 'userUpdatedByIdAdmin', methods: 'PUT')]
    public function userUpdatedByIdAdmin(int $id): JsonResponse
    {
        $data = $this->globals->jsondecode();
        $user = $this->userRepository->findOneBy(['id'=>$id]);

        //Verification des inputs reçu
        if (!isset($data->email,$data->username,$data->nom,$data->prenom,$data->numeroRue,$data->nomRue,$data->adresse,$data->telephone))
            return $this->globals->error(ErrorHttp::FORM_ERROR);

        if (!$user)
            return $this->globals->error(ErrorHttp::ID_ERROR);

        $user->setEmail($data->email)
            ->setUsername($data->username)
            ->setNom($data->nom)
            ->setPrenom($data->prenom)
            ->setNumeroRue($data->numeroRue)
            ->setNomRue($data->nomRue)
            ->setAdresse($data->adresse)
            ->setTelephone($data->telephone);

        $this->userRepository->updated(true);
        //printf($user);
        return $this->globals->success([
            "message"=> "Mise à jour effectuer avec success"
        ]);
    }

    #[Route('/userEnableOrDisableAdmin/{id}', name: 'userEnableOrDisableById', methods: 'PUT')]
    public function userEnableOrDisableById(int $id): JsonResponse
    {
        $data = $this->globals->jsondecode();
        $user = $this->userRepository->findOneBy(['id'=>$id]);

        //Verification des inputs reçu
        if (!isset($data->active))
            return $this->globals->error(ErrorHttp::FORM_ERROR);

        if (!$user)
            return $this->globals->error(ErrorHttp::ID_ERROR);

        if ($data->active === $user->getActive())
        {
            $user->setActive("1");
            $this->userRepository->updated(true);
            //printf($user);
            return $this->globals->success([
                "message"=> "Le compte à été activer avec success"
            ]);
        }

        $user->setActive("0");

        $this->userRepository->updated(true);
        //printf($user);
        return $this->globals->success([
            "message"=> "Le compte à été desactiver avec success"
        ]);
    }

    #[Route('/userUpdatedImageAdmin', name: 'userUpdatedImageById', methods: 'POST')]
    public function userUpdatedImageById(Request $request): JsonResponse
    {
        $uploadedFile = $request->files->get('file');
        $idUser = $request->get('id');

        $user = $this->userRepository->findOneBy(['id'=> $idUser]);

        if (!$user)
            return $this->globals->error(ErrorHttp::ID_ERROR);

        
        $image = $this->fileUploader->upload($uploadedFile);        

        $user->setImage($image);

        $this->userRepository->updated(true);

        return $this->globals->success(null,"La photo à été mise a jour");
    }

    #[Route('/deleteuser/{id}', name: 'userDelete', methods: 'DELETE')]
    public function userDelete(int $id): JsonResponse
    {

        $user = $this->userRepository->findOneBy(['id'=>$id]);

        if (!$user)
            return $this->globals->error(ErrorHttp::ID_DELETE_ERROR);

        $this->userRepository->remove($user, true);
        //printf($user);
        return $this->globals->success([
            "message"=> "L'utilisateur à été supprimer!"
        ]);
    }


}