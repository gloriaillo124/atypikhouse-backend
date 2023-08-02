<?php

namespace App\Controller\Api\website;

use App\Entity\Avis;
use App\Repository\AvisRepository;
use App\Repository\LogementRepository;
use App\Repository\UserRepository;
use App\Utils\ErrorHttp;
use App\Utils\Globals;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
#[Route('api/website')]
class AvisController extends AbstractController
{
    private Globals $globals;
    private UserRepository $userRepository;
    private LogementRepository $logementRepository;
    private AvisRepository $avisRepository;

    public function __construct(Globals $globals,AvisRepository $avisRepository,LogementRepository $logementRepository,UserRepository $userRepository)
    {
        $this->globals = $globals;
        $this->logementRepository = $logementRepository;
        $this->userRepository = $userRepository;
        $this->avisRepository = $avisRepository;
    }

    #[Route('/avis/{id}', name: 'avisById', methods: 'GET')]
    public function avisById(int $id): JsonResponse
    {
        $avis = $this->avisRepository->findBy(['fk_logement'=>$id]);

        if (!$avis)
            return $this->globals->error(ErrorHttp::ID_REQUESTE_ERROR);
        //printf($user);
        return $this->globals->getData(array_map(function (Avis $avis){
            return $avis->toArray();
        },$avis));
    }

    #[Route('/save-avis', name: 'saveAvis', methods: 'POST')]
    public function saveAvis(): JsonResponse
    {
        $data = $this->globals->jsondecode();

        //Verification des inputs reçu
        if (!isset($data->valeurEtoile,$data->idLogement,$data->idUser))
            return $this->globals->error(ErrorHttp::FORM_ERROR);

        $logement = $this->logementRepository->findOneBy(['id'=>$data->idLogement]);

        $user = $this->userRepository->findOneBy(['id'=>$data->idUser]);

        $avis = new Avis();

        //Enregistrement
        $avis->setValeurEtoile($data->valeurEtoile)
            ->setFkLogement($logement)
            ->setFkUser($user)
            ->setCreatedat($this->globals->dateNow());

        $this->avisRepository->save($avis,true);

        return $this->globals->success([
            "message"=> "Avis à été ajouter"
        ]);
    }
}