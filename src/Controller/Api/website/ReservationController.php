<?php

namespace App\Controller\Api\website;

use App\Entity\Reservation;
use App\Repository\CodePromoRepository;
use App\Repository\LogementRepository;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use App\Utils\ErrorHttp;
use App\Utils\Globals;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/website')]
class ReservationController extends AbstractController
{

    private Globals $globals;
    private LogementRepository $logementRepository;

    private CodePromoRepository $codePromoRepository;

    private UserRepository $userRepository;

    private ReservationRepository $reservationRepository;

    public function __construct(Globals $globals, LogementRepository $logementRepository, CodePromoRepository $codePromoRepository,UserRepository $userRepository,ReservationRepository $reservationRepository)
    {
        $this->globals = $globals;
        $this->logementRepository = $logementRepository;
        $this->codePromoRepository = $codePromoRepository;
        $this->userRepository = $userRepository;
        $this->reservationRepository = $reservationRepository;
    }

    #[Route('/save-reservationSite', name: 'saveReservationSite', methods: 'POST')]
    public function saveReservationSite(): JsonResponse
    {
        $data = $this->globals->jsondecode();

        //Verification des inputs reçu
        if (!isset($data->idLogement, $data->idUser, $data->dateDepart, $data->dateArriver, $data->codePromo,$data->enableCodepromo))
            return $this->globals->error(ErrorHttp::FORM_ERROR);

        $reservation = $this->reservationRepository->findOneBy(['fk_logement'=>$data->idLogement,'fk_user'=>$data->idUser]);

        if ($reservation) {
            return $this->globals->success([
                "message"=> "La réservation à été effectuer"
            ]);
        }

        $logement = $this->logementRepository->findOneBy(['id'=>$data->idLogement]);

        $user = $this->userRepository->findOneBy(['id'=>$data->idUser]);

        if ($data->enableCodepromo == true)
        {
            $codepromo = $this->codePromoRepository->findOneBy(['libelle'=>$data->codePromo,'fk_logement'=>$data->idLogement]);

            if (!$codepromo)
                return $this->globals->error([
                    "message" => "Ce code promo est invalide"
                ]);

            $reservation = new Reservation();

            //Enregistrement
            $reservation->setFkUser($user)
                ->setFkLogement($logement)
                ->setPaiement($logement->getMontant())
                ->setUserInsolite($logement->getUserId())
                ->setCreatedat($this->globals->dateNow())
                ->setFkCodePromo($codepromo)
                ->setDateDepart($data->dateDepart)
                ->setDateArrive($data->dateArriver);

            $this->reservationRepository->save($reservation,true);

            return $this->globals->success([
                "message"=> "La réservation à été effectuer"
            ]);
        }

        $reservation = new Reservation();

        //Enregistrement
        $reservation->setFkUser($user)
            ->setFkLogement($logement)
            ->setPaiement($logement->getMontant())
            ->setUserInsolite($logement->getUserId())
            ->setCreatedat($this->globals->dateNow())
            ->setDateDepart($data->dateDepart)
            ->setDateArrive($data->dateArriver);

        $this->reservationRepository->save($reservation,true);

        return $this->globals->success([
            "message"=> "La réservation à été effectuer"
        ]);
    }
}