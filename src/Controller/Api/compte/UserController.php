<?php

namespace App\Controller\Api\compte;

use App\Entity\User;
use App\Repository\PartenaireRepository;
use App\Repository\UserRepository;
use App\Utils\ErrorHttp;
use App\Utils\Globals;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/users')]
class UserController extends AbstractController
{
    private Globals $globals;
    private UserRepository $userRepository;

    public function __construct(Globals $globals, UserRepository $userRepository)
    {
        $this->globals = $globals;
        $this->userRepository = $userRepository;
    }

    #[Route('/save-client', name: 'userSaveClient', methods: 'POST')]
    public function userSaveClient(): JsonResponse
    {
        $data = $this->globals->jsondecode();

        //Verification des inputs reçu
        if (!isset($data->email,$data->username,$data->password,$data->nom,$data->prenom,$data->date_naissance,$data->numero_rue,$data->nom_rue,$data->adresse,$data->sexe,$data->telephone))
            return $this->globals->error(ErrorHttp::FORM_ERROR);

        $userExist = $this->userRepository->findOneBy(['email'=>$data->email]);

        if ($userExist)
            return $this->globals->error([
                "message" => "Cet utilisateur existe déja"
            ]);

       $user = new User();

       //encoder les mots de passe des utilisateurs avant de les insérer
       $encodedPassword = $this->globals->hasher()->hashPassword($user, $data->password);

       //Enregistrement de l'utilisateur
        $user->setEmail($data->email)
            ->setUsername($data->username)
            ->setPassword($encodedPassword)
            ->setNom($data->nom)
            ->setPrenom($data->prenom)
            ->setDateNaissance($data->date_naissance)
            ->setNumeroRue($data->numero_rue)
            ->setNomRue($data->nom_rue)
            ->setAdresse($data->adresse)
            ->setRoleUser('ROLE_CLIENT')
            ->setSexe($data->sexe)
            ->setCreated($this->globals->dateNow())
            ->setTelephone($data->telephone);

        $this->userRepository->save($user,true);

        return $this->globals->success([
            "message"=> "Le compte à été créer avec success"
        ]);
    }

    #[Route('/save-hote', name: 'userSaveHote', methods: 'POST')]
    public function userSaveHote(): JsonResponse
    {
        $data = $this->globals->jsondecode();

        //Verification des inputs reçu
        if (!isset($data->email,$data->username,$data->password,$data->nom,$data->prenom,$data->date_naissance,$data->numero_rue,$data->nom_rue,$data->adresse,$data->sexe,$data->telephone))
            return $this->globals->error(ErrorHttp::FORM_ERROR);

        $userExist = $this->userRepository->findOneBy(['email'=>$data->email]);

        if ($userExist)
            return $this->globals->error([
                "message" => "Cet utilisateur existe déja"
            ]);

       $user = new User();

       //encoder les mots de passe des utilisateurs avant de les insérer
       $encodedPassword = $this->globals->hasher()->hashPassword($user, $data->password);

       //Enregistrement de l'utilisateur
        $user->setEmail($data->email)
            ->setUsername($data->username)
            ->setPassword($encodedPassword)
            ->setNom($data->nom)
            ->setPrenom($data->prenom)
            ->setDateNaissance($data->date_naissance)
            ->setNumeroRue($data->numero_rue)
            ->setNomRue($data->nom_rue)
            ->setAdresse($data->adresse)
            ->setRoleUser('ROLE_HOTE')
            ->setSexe($data->sexe)
            ->setCreated($this->globals->dateNow())
            ->setPartenaire("1")
            ->setTelephone($data->telephone);

        $this->userRepository->save($user,true);

        return $this->globals->success([
            "message"=> "Le compte à été créer avec success"
        ]);
    }

    #[Route('/save-admin', name: 'userSaveAdmin', methods: 'POST')]
    public function userSaveAdmin(): JsonResponse
    {
        $data = $this->globals->jsondecode();

        //Verification des inputs reçu
        if (!isset($data->email,$data->username,$data->password,$data->nom,$data->prenom,$data->date_naissance,$data->numero_rue,$data->nom_rue,$data->adresse,$data->sexe,$data->telephone))
            return $this->globals->error(ErrorHttp::FORM_ERROR);

        $userExist = $this->userRepository->findOneBy(['email'=>$data->email]);

        if ($userExist)
            return $this->globals->error([
                "message" => "Cet utilisateur existe déja"
            ]);

       $user = new User();

       //encoder les mots de passe des utilisateurs avant de les insérer
       $encodedPassword = $this->globals->hasher()->hashPassword($user, $data->password);

       //Enregistrement de l'utilisateur
        $user->setEmail($data->email)
            ->setUsername($data->username)
            ->setPassword($encodedPassword)
            ->setNom($data->nom)
            ->setPrenom($data->prenom)
            ->setDateNaissance($data->date_naissance)
            ->setNumeroRue($data->numero_rue)
            ->setNomRue($data->nom_rue)
            ->setAdresse($data->adresse)
            ->setRoleUser('ROLE_ADMIN')
            ->setSexe($data->sexe)
            ->setCreated($this->globals->dateNow())
            ->setTelephone($data->telephone);

        $this->userRepository->save($user,true);

        return $this->globals->success([
            "message"=> "Le compte à été créer avec success"
        ]);
    }


}