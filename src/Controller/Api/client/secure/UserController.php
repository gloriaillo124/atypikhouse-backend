<?php

namespace App\Controller\Api\client\secure;

use App\Repository\UserRepository;
use App\Service\FileUploader;
use App\Utils\ErrorHttp;
use App\Utils\Globals;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/client/secure')]
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

    #[Route('/userOneClient/{id}', name: 'userOneByIdClient', methods: 'GET')]
    public function userOneByIdClient(int $id): JsonResponse
    {
        $user = $this->userRepository->findOneBy(['id'=>$id]);
        //printf($user);
        return $this->globals->getOnData($user?->toArray());
    }

    #[Route('/userUpdatedClient/{id}', name: 'userUpdatedByIdClient', methods: 'PUT')]
    public function userUpdatedByIdClient(int $id): JsonResponse
    {
        $data = $this->globals->jsondecode();
        $user = $this->userRepository->findOneBy(['id'=>$id]);

        //Verification des inputs reçu
        if (!isset($data->email,$data->username,$data->nom,$data->prenom,$data->date_naissance,$data->numero_rue,$data->nom_rue,$data->adresse,$data->sexe,$data->telephone))
            return $this->globals->error(ErrorHttp::FORM_ERROR);

        if (!$user)
            return $this->globals->error(ErrorHttp::ID_ERROR);

        $user->setEmail($data->email)
            ->setUsername($data->username)
            ->setNom($data->nom)
            ->setPrenom($data->prenom)
            ->setDateNaissance($data->date_naissance)
            ->setNumeroRue($data->numero_rue)
            ->setNomRue($data->nom_rue)
            ->setAdresse($data->adresse)
            ->setSexe($data->sexe)
            ->setTelephone($data->telephone);

        $this->userRepository->updated(true);
        //printf($user);
        return $this->globals->success([
            "message"=> "Mise à jour effectuer avec success"
        ]);
    }

    #[Route('/userUpdatedImageClient', name: 'userUpdatedImageByIdClient', methods: 'POST')]
    public function userUpdatedImageByIdClient(Request $request): JsonResponse
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


}