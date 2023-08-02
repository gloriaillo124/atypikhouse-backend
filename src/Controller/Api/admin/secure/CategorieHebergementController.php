<?php

namespace App\Controller\Api\admin\secure;

use App\Entity\CategorieHebergement;
use App\Repository\CategorieHebergementRepository;
use App\Service\FileUploaderHebergement;
use App\Utils\ErrorHttp;
use App\Utils\Globals;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('api/admins/secure')]
class CategorieHebergementController extends AbstractController
{

    private Globals $globals;
    private CategorieHebergementRepository $hebergementRepository;
    private FileUploaderHebergement $uploadedFileHebrgement;

    public function __construct(Globals $globals, CategorieHebergementRepository $hebergementRepository, FileUploaderHebergement $uploadedFileHebrgement)
    {
        $this->globals = $globals;
        $this->hebergementRepository = $hebergementRepository;
        $this->uploadedFileHebrgement = $uploadedFileHebrgement;
    }

    #[Route('/categoriehebergementAdmin', name: 'hebergementAllAdmin', methods: 'GET')]
    public function hebergementAllAdmin(): JsonResponse
    {
        return $this->globals->getData(array_map(function (CategorieHebergement $hebergement){
            return $hebergement->toArray();
        },$this->hebergementRepository->findAll()));
    }

    #[Route('/hebergementOneByIdAdmin/{id}', name: 'oneByIdHebergementAdmin', methods: 'GET')]
    public function oneByIdHebergementAdmin(int $id): JsonResponse
    {
        $hebergement = $this->hebergementRepository->findOneBy(['id'=>$id]);
        //printf($user);
        return $this->globals->getOnData($hebergement?->toArray());
    }

    #[Route('/save-categoriehebergementAdmin', name: 'saveHebergementAdmin', methods: 'POST')]
    public function saveHebergementAdmin(Request $request): JsonResponse
    {
        $uploadedFile = $request->files->get('file');
        $libelle = $request->get('libelle');
        $description = $request->get('description');
        // $data = $this->globals->jsondecode();

        // //Verification des inputs reçu
        // if (!isset($data->libelle,$data->description))
        //     return $this->globals->error(ErrorHttp::FORM_ERROR);

        $hebergementExist = $this->hebergementRepository->findOneBy(['libelle'=>$libelle]);

        if ($hebergementExist)
            return $this->globals->error([
                "message" => "Cet catégorie d'hebergement existe déja",
                'codeHttp'=> 200
            ]);

        $image = $this->uploadedFileHebrgement->upload($uploadedFile); 

        $hebergement = new CategorieHebergement();

        //Enregistrement
        $hebergement->setLibelle($libelle)
            ->setDescription($description)
            ->setImage($image);

        $this->hebergementRepository->save($hebergement,true);

        return $this->globals->success(null,"La catégorie d'hebergement à été créer avec success");
    }

    #[Route('/categoriehebergementUpdatedAdmin/{id}', name: 'updatedHebergementByIdAdmin', methods: 'PUT')]
    public function updatedHebergementByIdAdmin(int $id): JsonResponse
    {
        $data = $this->globals->jsondecode();
        $hebergement = $this->hebergementRepository->findOneBy(['id'=>$id]);

        //Verification des inputs reçu
        if (!isset($data->libelle,$data->description))
            return $this->globals->error(ErrorHttp::FORM_ERROR);

        if (!$hebergement)
            return $this->globals->error(ErrorHttp::ID_ERROR);

        $hebergement->setLibelle($data->libelle)
            ->setDescription($data->description);

        $this->hebergementRepository->updated(true);
        //printf($user);
        return $this->globals->success(null, "La categorie à été mise a jour");
    }

    #[Route('/delete-categoriehebergementAdmin/{id}', name: 'hebergementDeleteAdmin', methods: 'DELETE')]
    public function hebergementDeleteAdmin(int $id): JsonResponse
    {

        $hebergement = $this->hebergementRepository->findOneBy(['id'=>$id]);

        if (!$hebergement)
            return $this->globals->error(ErrorHttp::ID_DELETE_ERROR);

        $this->hebergementRepository->remove($hebergement, true);
        //printf($user);
        return $this->globals->success(null,"La catégorie d'hebergement à été supprimer avec success");
    }

}