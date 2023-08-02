<?php

namespace App\Controller\Api\admin\secure;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use App\Repository\LogementRepository;
use App\Utils\ErrorHttp;
use App\Utils\Globals;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
#[Route('api/admins/secure')]
class ReservationController extends AbstractController
{
    private Globals $globals;

    private ReservationRepository $reservationRepository;

    private LogementRepository $logementRepository;

    public function __construct(Globals $globals, ReservationRepository $reservationRepository,LogementRepository $logementRepository)
    {
        $this->globals = $globals;
        $this->reservationRepository = $reservationRepository;
        $this->logementRepository = $logementRepository;
    }

    #[Route('/reservations', name: 'reservationsAll', methods: 'GET')]
    public function reservationsAll(): JsonResponse
    {
        return $this->globals->getData(array_map(function (Reservation $reservation){
            return $reservation->toArray();
        },$this->reservationRepository->findAll()));
    }

    #[Route('/reservationsByIdAdmin/{id}', name: 'reservationsAllByIdAdmin', methods: 'GET')]
    public function reservationsAllByIdAdmin(int $id): JsonResponse
    {
        return $this->globals->getData(array_map(function (Reservation $reservation){
            return $reservation->toArray();
        },$this->reservationRepository->findBy(['userInsolite'=>$id])));
    }

    #[Route('/reservationsClientByIdAdmin/{id}', name: 'reservationsClientAllByIdAdmin', methods: 'GET')]
    public function reservationsClientAllByIdAdmin(int $id): JsonResponse
    {
        return $this->globals->getData(array_map(function (Reservation $reservation){
            return $reservation->toArray();
        },$this->reservationRepository->findBy(['fk_user'=>$id])));
    }

    #[Route('/reservationsClientOccupeByIdAdmin/{id}', name: 'reservationsClientOccupeAllByIdAdmin', methods: 'GET')]
    public function reservationsClientOccupeAllByIdAdmin(int $id): JsonResponse
    {
        return $this->globals->getData(array_map(function (Reservation $reservation){
            return $reservation->toArray();
        },$this->reservationRepository->findBy(['fk_user'=>$id, 'confirme'=> '1'])));
    }

    #[Route('/reservationsOccupeByIdAdmin/{id}', name: 'reservationsOccupeAllByIdAdmin', methods: 'GET')]
    public function reservationsOccupeAllByIdAdmin(int $id): JsonResponse
    {
        return $this->globals->getData(array_map(function (Reservation $reservation){
            return $reservation->toArray();
        },$this->reservationRepository->findBy(['userInsolite'=>$id, 'confirme'=> '1'])));
    }

    #[Route('/reservationConfirmeAdmin/{id}', name: 'reservationConfirmeByIdAdmin', methods: 'PUT')]
    public function reservationConfirmeByIdAdmin(int $id): JsonResponse
    {
        $data = $this->globals->jsondecode();

        if (!isset($data->confirme))
            return $this->globals->error(ErrorHttp::FORM_ERROR);

        $reservation = $this->reservationRepository->findOneBy(['id'=>$id]);

        if (!$reservation)
            return $this->globals->error(ErrorHttp::ID_ERROR);

        $logement = $this->logementRepository->findOneBy(['id'=>$reservation->getFkLogement()->getId()]);

        $logement->setDisponible("2");

        $this->logementRepository->updated(true);

        $reservation->setConfirme($data->confirme);

        $this->reservationRepository->updated(true);
        //printf($user);
        return $this->globals->success(null, "La reservation à été accepter");
    }

    #[Route('/reservationAnnulerAdmin/{id}', name: 'reservationAnnulerByIdAdmin', methods: 'PUT')]
    public function reservationAnnulerByIdAdmin(int $id): JsonResponse
    {
        $data = $this->globals->jsondecode();

        if (!isset($data->confirme))
            return $this->globals->error(ErrorHttp::FORM_ERROR);

        $reservation = $this->reservationRepository->findOneBy(['id'=>$id]);

        if (!$reservation)
            return $this->globals->error(ErrorHttp::ID_ERROR);

        $reservation->setConfirme($data->confirme);

        $this->reservationRepository->updated(true);
        //printf($user);
        return $this->globals->success(null, "La reservation à été annuler");
    }

    #[Route('/reservationClotureAdmin/{id}', name: 'reservationClotureByIdAdmin', methods: 'PUT')]
    public function reservationClotureByIdAdmin(int $id): JsonResponse
    {
        $data = $this->globals->jsondecode();

        if (!isset($data->confirme))
            return $this->globals->error(ErrorHttp::FORM_ERROR);

        // $reservation = $this->reservationRepository->findOneBy(['id'=>$id]);

        // if (!$reservation)
        //     return $this->globals->error(ErrorHttp::ID_ERROR);

        $logement = $this->logementRepository->findOneBy(['id'=>$id]);

        $logement->setDisponible($data->confirme);

        $this->logementRepository->updated(true);
        //printf($user);
        return $this->globals->success(null, "La reservation est maintenant disponible");
    }

}