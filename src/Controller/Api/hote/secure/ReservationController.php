<?php

namespace App\Controller\Api\hote\secure;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use App\Utils\Globals;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
#[Route('api/hote/secure')]
class ReservationController extends AbstractController
{
    private Globals $globals;

    private ReservationRepository $reservationRepository;

    public function __construct(Globals $globals, ReservationRepository $reservationRepository)
    {
        $this->globals = $globals;
        $this->reservationRepository = $reservationRepository;
    }

    #[Route('/reservationsHote/{id}', name: 'reservationsAllByIdHote', methods: 'GET')]
    public function reservationsAllByIdHote(int $id): JsonResponse
    {
        return $this->globals->getData(array_map(function (Reservation $reservation){
            return $reservation->toArray();
        },$this->reservationRepository->findBy(['fk_user'=>$id])));
    }
}