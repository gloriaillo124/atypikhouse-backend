<?php

namespace App\Controller\Api\website;

use App\Entity\Commentaire;
use App\Repository\CommentaireRepository;
use App\Repository\LogementRepository;
use App\Repository\UserRepository;
use App\Utils\ErrorHttp;
use App\Utils\Globals;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/website')]
class CommentaireController extends AbstractController
{
    private Globals $globals;
    private UserRepository $userRepository;
    private LogementRepository $logementRepository;
    private CommentaireRepository $commentaireRepository;

    public function __construct(Globals $globals,CommentaireRepository $commentaireRepository,LogementRepository $logementRepository,UserRepository $userRepository)
    {
        $this->globals = $globals;
        $this->logementRepository = $logementRepository;
        $this->userRepository = $userRepository;
        $this->commentaireRepository = $commentaireRepository;
    }

    #[Route('/commentairesSite/{id}', name: 'commentairesByIdSite', methods: 'GET')]
    public function commentairesByIdSite(int $id): JsonResponse
    {
        $commentaires = $this->commentaireRepository->findBy(['fk_logement'=>$id]);

        if (!$commentaires)
            return $this->globals->error(ErrorHttp::ID_REQUESTE_ERROR);
        //printf($user);
        return $this->globals->getData(array_map(function (Commentaire $commentaire){
            return $commentaire->toArray();
        },$commentaires));
    }

    #[Route('/save-commentaire', name: 'saveCommentaire', methods: 'POST')]
    public function saveCommentaire(): JsonResponse
    {
        $data = $this->globals->jsondecode();

        //Verification des inputs reçu
        if (!isset($data->message,$data->idLogement,$data->idUser))
            return $this->globals->error(ErrorHttp::FORM_ERROR);

        $logement = $this->logementRepository->findOneBy(['id'=>$data->idLogement]);

        $user = $this->userRepository->findOneBy(['id'=>$data->idUser]);

        $commentaire = new Commentaire();

        //Enregistrement
        $commentaire->setMessage($data->message)
            ->setFkLogement($logement)
            ->setFkUser($user)
            ->setCreated($this->globals->dateNow());

        $this->commentaireRepository->save($commentaire,true);

        return $this->globals->success(null, "Commentaire à été ajouter");
    }

    #[Route('/delete-commentaire/{id}', name: 'commentaireDelete', methods: 'DELETE')]
    public function commentaireDelete(int $id): JsonResponse
    {

        $commentaire = $this->commentaireRepository->findOneBy(['id'=>$id]);

        if (!$commentaire)
            return $this->globals->error(ErrorHttp::ID_DELETE_ERROR);

        $this->commentaireRepository->remove($commentaire, true);
        //printf($user);
        return $this->globals->success(null, "Le commentaire à été retirer");
    }
}