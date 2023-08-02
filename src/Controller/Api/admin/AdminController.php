<?php

namespace App\Controller\Api\admin;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\CategorieHebergementRepository;
use App\Repository\CategorieDestinationRepository;
use App\Repository\CategorieInspirationRepository;
use App\Repository\LogementRepository;
use App\Repository\ReservationRepository;
use App\Repository\CommentaireRepository;
use App\Utils\ErrorHttp;
use App\Utils\Globals;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('api/admins')]
class AdminController extends AbstractController
{
    private Globals $globals;
    private UserRepository $userRepository;
    private CategorieHebergementRepository $hebergementRepository;
    private CategorieDestinationRepository $destinationRepository;
    private CategorieInspirationRepository $inspirationRepository;
    private LogementRepository $logementRepository;
    private ReservationRepository $reservationRepository;
    private CommentaireRepository $commentaireRepository;

    public function __construct(Globals $globals,CommentaireRepository $commentaireRepository,ReservationRepository $reservationRepository, UserRepository $userRepository,CategorieHebergementRepository $hebergementRepository,CategorieDestinationRepository $destinationRepository,CategorieInspirationRepository $inspirationRepository,LogementRepository $logementRepository)
    {
        $this->globals = $globals;
        $this->userRepository = $userRepository;
        $this->destinationRepository = $destinationRepository;
        $this->hebergementRepository = $hebergementRepository;
        $this->inspirationRepository = $inspirationRepository;
        $this->logementRepository = $logementRepository;
        $this->reservationRepository = $reservationRepository;
        $this->commentaireRepository = $commentaireRepository;
    }

    #[Route('/usersAdmin', name: 'usersAllAdmins', methods: 'GET')]
    public function usersAllAdmins(): JsonResponse
    {
         return $this->globals->getData(array_map(function (User $user){
             return $user->toArray();
        },$this->userRepository->findBy(['roleUser'=>"ROLE_ADMIN"])));
    }

    #[Route('/usersClient', name: 'usersAllClient', methods: 'GET')]
    public function usersAllClient(): JsonResponse
    {
         return $this->globals->getData(array_map(function (User $user){
             return $user->toArray();
        },$this->userRepository->findBy(['roleUser'=>"ROLE_CLIENT"])));
    }

    #[Route('/usersPartenaire', name: 'usersAllPartenaire', methods: 'GET')]
    public function usersAllPartenaire(): JsonResponse
    {
         return $this->globals->getData(array_map(function (User $user){
             return $user->toArray();
        },$this->userRepository->findBy(['roleUser'=>"ROLE_HOTE"])));
    }

    #[Route('/countTableaubordAdmin', name: 'usersAllPartenaire', methods: 'GET')]
    public function alltableauBordAdmin(): JsonResponse
    {
        // $clients = $this->userRepository->findBy(['roleUser'=>"ROLE_CLIENT"]);
        // $partenaires = $this->userRepository->findBy(['roleUser'=>"ROLE_HOTE"]);
        // $admins = $this->userRepository->findBy(['roleUser'=>"ROLE_ADMIN"]);

        $totalPartenaires = $this->userRepository->createQueryBuilder('a')
            ->where('a.partenaire = 1')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();   
            
        $totalClients = $this->userRepository->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult(); 
            
        $hebergementTotal = $this->hebergementRepository->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
        
        $destinationTotal = $this->destinationRepository->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
        
        $inspirationTotal = $this->inspirationRepository->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $insoliteTotal = $this->logementRepository->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $reservationTotal = $this->reservationRepository->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $commentaioreTotal = $this->commentaireRepository->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();

        return $this->globals->getData([
            "partenairesTotal" => $totalPartenaires,
            "usersTotal" => $totalClients,
            "hebergementTotal" => $hebergementTotal,
            "destinationTotal" => $destinationTotal,
            "inspirationTotal" => $inspirationTotal,
            "insoliteTotal" => $insoliteTotal,
            "reservationTotal" => $reservationTotal,
            "commentaioreTotal" => $commentaioreTotal,
         ]);
    }

}