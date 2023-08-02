<?php

namespace App\Controller\Api\connexion;

use App\Repository\UserRepository;
use App\Utils\ErrorHttp;
use App\Utils\Globals;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('api/security')]
class SecurityController extends AbstractController
{

    private Globals $globals;
    private UserRepository $userRepository;
    private JWTTokenManagerInterface $tokenManager;

    public function __construct(Globals $globals, UserRepository $userRepository, JWTTokenManagerInterface $tokenManager)
    {
        $this->globals = $globals;
        $this->userRepository = $userRepository;
        $this->tokenManager = $tokenManager;
    }

    #[Route('/login', name: 'login', methods: 'POST')]
    public function login(): JsonResponse
    {
        $data = $this->globals->jsondecode();

        if (!isset($data->email, $data->password) || ($data->email === "" || $data->password === ""))
            return $this->globals->error(ErrorHttp::FORM_ERROR);

        $user = $this->userRepository->findOneBy(['email'=>$data->email]);

        //print("user");

        if (!$user)
            return $this->globals->error(ErrorHttp::USER_ERROR);

        if ($user->getActive()=="0")
            return $this->globals->error(ErrorHttp::USER_DISABLE_ERROR);

        if (!$this->globals->hasher()->isPasswordValid($user, $data->password))
            return $this->globals->error(ErrorHttp::PASSWORD_ERROR);

        return $this->globals->success($user->toArray(),"Vous êtes connecté");
    }

    #[Route('/updatedPasswordByEmail/{email}', name: 'updatedPasswordByEmail', methods: 'PUT')]
    public function updatedPasswordByEmail(string $email): JsonResponse
    {
        $data = $this->globals->jsondecode();
        $user = $this->userRepository->findOneBy(['email'=>$email]);

        //Verification des inputs reçu
        if (!isset($data->password))
            return $this->globals->error(ErrorHttp::FORM_ERROR);

        if (!$user)
            return $this->globals->error(ErrorHttp::ID_ERROR);

        //encoder le mots de passe
        $encodedPassword = $this->globals->hasher()->hashPassword($user, $data->password);
        $user->setPassword($encodedPassword);

        $this->userRepository->updated(true);
        //printf($user);
        return $this->globals->success(null,"Le mot de passe à été mise à jour avec success");
    }



}