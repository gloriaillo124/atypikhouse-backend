<?php

namespace App\Utils;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use DateTime;

class Globals
{
    private UserPasswordHasherInterface $harsher;


    public function __construct(UserPasswordHasherInterface $harsher)
    {
        $this->harsher = $harsher;
    }

    /**
     * @param array|null $data
     * @param $message
     * @param int $codeHttp
     * @return JsonResponse
     */
    public function success(array $data = null,$message = 'success', int $codeHttp = 200 ): JsonResponse
    {
        return new JsonResponse([
            "status" => 1,
            "message" => $message,
            "data" => $data
        ], $codeHttp);
    }

    public function getData(array $data = null, int $codeHttp = 200 ): JsonResponse
    {
        return new JsonResponse($data, $codeHttp);
    }

    public function getOnData( $data = null, int $codeHttp = 200 ): JsonResponse
    {
        return new JsonResponse($data, $codeHttp);
    }

    /**
     *
     * @param array $error
     * @return JsonResponse
     */
    public function error(array $error = ErrorHttp::ERROR): JsonResponse
    {
        return new JsonResponse([
            "status" => 0,
            "message" => $error['message'] ?? 'error',
        ], $error['codeHttp']);
    }

    /*
     * Permet de capture tous les champs envoyer par une requete POST
     */
    public function jsondecode()
    {
        try {
            return file_get_contents('php://input') ?
                json_decode(file_get_contents('php://input'), false) : [];
        }catch (\Exception $e){
            return [];
        }
    }

    public function hasher()
    {
        return $this->harsher;
    }

    public function dateNow()
    {
        $date = new DateTime();
        return $date->format('d/m/Y');
    }

    public function dateTimeNow()
    {
        $date = new DateTime();
        return $date->format('d/m/Y h:i');
    }
}