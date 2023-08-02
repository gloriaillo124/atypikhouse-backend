<?php

namespace App\Controller\Api\website;

use App\Entity\LogementOption;
use App\Entity\Option;
use App\Entity\Reservation;
use App\Repository\LogementOptionRepository;
use App\Repository\LogementRepository;
use App\Repository\OptionRepository;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use App\Utils\ErrorHttp;
use App\Utils\Globals;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/website')]
class LogementOptionController extends AbstractController
{

    private Globals $globals;

    private LogementRepository $logementRepository;

    private OptionRepository $optionRepository;

    private LogementOptionRepository $logementOptionRepository;

    private ReservationRepository $reservationRepository;

    private UserRepository $userRepository;

    public function __construct(Globals $globals, LogementRepository $logementRepository, OptionRepository $optionRepository,LogementOptionRepository $logementOptionRepository,ReservationRepository $reservationRepository,UserRepository $userRepository)
    {
        $this->globals = $globals;
        $this->logementRepository = $logementRepository;
        $this->optionRepository = $optionRepository;
        $this->logementOptionRepository = $logementOptionRepository;
        $this->reservationRepository = $reservationRepository;
        $this->userRepository = $userRepository;
    }

    #[Route('/options', name: 'optionAll', methods: 'GET')]
    public function optionAll(): JsonResponse
    {
        return $this->globals->getData(array_map(function (Option $option){
            return $option->toArray();
        },$this->optionRepository->findAll()));
    }

    #[Route('/logementoptionAllSite/{id}', name: 'logementoptionAllSite', methods: 'GET')]
    public function logementoptionAllSite(int $id): JsonResponse
    {
        return $this->globals->getData(array_map(function (LogementOption $logementOption){
            return $logementOption->toArray();
        },$this->logementOptionRepository->findBy(['fk_logement'=>$id])));
    }

    #[Route('/save-logementoptionSite', name: 'saveLogementOptionSite', methods: 'POST')]
    public function saveLogementOptionSite(): JsonResponse
    {
        $data = $this->globals->jsondecode();

        //Verification des inputs reçu
        if (!isset($data->idLogement, $data->idOption, $data->optionValeur, $data->idUser, $data->dateDepart, $data->dateArriver))
            return $this->globals->error(ErrorHttp::FORM_ERROR);

        $logementOptionExist = $this->logementOptionRepository->findOneBy(['fk_option'=>$data->idOption]);

        if ($logementOptionExist)
            return $this->globals->error([
                "message" => "Cet option existe déja",
                'codeHttp'=> 200
            ]);

        $reservation = $this->reservationRepository->findOneBy(['fk_logement'=>$data->idLogement,'fk_user'=>$data->idUser]);

        $logement = $this->logementRepository->findOneBy(['id'=>$data->idLogement]);

        $option = $this->optionRepository->findOneBy(['id'=>$data->idOption]);

        $user = $this->userRepository->findOneBy(['id'=>$data->idUser]);

        $logementOption = new LogementOption();

        if ($reservation){

            //Enregistrement
            $logementOption->setFkOption($option)
                ->setFkLogement($logement)
                ->setOptionValeur($data->optionValeur);
    
            $this->logementOptionRepository->save($logementOption,true);
    
            return $this->globals->success(null,"L'option à été créer avec success");
        }

        //$logementOption = new LogementOption();

        $reservation = new Reservation();

        //Enregistrement
        $reservation->setFkUser($user)
            ->setFkLogement($logement)
            ->setUserInsolite($logement->getUserId())
            ->setPaiement($logement->getMontant())
            ->setCreatedat($this->globals->dateNow())
            ->setDateDepart($data->dateDepart)
            ->setDateArrive($data->dateArriver);

        $this->reservationRepository->save($reservation,true);

        //Enregistrement
        $logementOption->setFkOption($option)
            ->setFkLogement($logement)
            ->setOptionValeur($data->optionValeur);

        $this->logementOptionRepository->save($logementOption,true);

        return $this->globals->success(null,"L'option à été créer avec success");
    }

    #[Route('/delete-logementOptionSite/{id}', name: 'logementOptionDeleteSite', methods: 'DELETE')]
    public function logementOptionDeleteSite(int $id): JsonResponse
    {

        $logementOption = $this->logementOptionRepository->findOneBy(['id'=>$id]);

        if (!$logementOption)
            return $this->globals->error(ErrorHttp::ID_DELETE_ERROR);

        $this->logementOptionRepository->remove($logementOption, true);
        //printf($user);
        return $this->globals->success(null,"L'option à été retirer");
    }
}