<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?Logement $fk_logement = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?User $fk_user = null;

    #[ORM\Column(length: 255)]
    private ?string $dateDepart = null;

    #[ORM\Column(length: 255)]
    private ?string $dateArrive = null;

    #[ORM\Column(length: 255)]
    private ?string $paiement = null;

    #[ORM\Column(length: 255)]
    private ?string $confirme = "0";

    #[ORM\Column(length: 255)]
    private ?string $createdat = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?CodePromo $fk_codePromo = null;

    #[ORM\Column(length: 255)]
    private ?string $userInsolite = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFkLogement(): ?Logement
    {
        return $this->fk_logement;
    }

    public function setFkLogement(?Logement $fk_logement): self
    {
        $this->fk_logement = $fk_logement;

        return $this;
    }

    public function getFkUser(): ?User
    {
        return $this->fk_user;
    }

    public function setFkUser(?User $fk_user): self
    {
        $this->fk_user = $fk_user;

        return $this;
    }

    public function getDateDepart(): ?string
    {
        return $this->dateDepart;
    }

    public function setDateDepart(string $dateDepart): self
    {
        $this->dateDepart = $dateDepart;

        return $this;
    }

    public function getDateArrive(): ?string
    {
        return $this->dateArrive;
    }

    public function setDateArrive(string $dateArrive): self
    {
        $this->dateArrive = $dateArrive;

        return $this;
    }

    public function getPaiement(): ?string
    {
        return $this->paiement;
    }

    public function setPaiement(string $paiement): self
    {
        $this->paiement = $paiement;

        return $this;
    }

    public function getConfirme(): ?string
    {
        return $this->confirme;
    }

    public function setConfirme(string $confirme): self
    {
        $this->confirme = $confirme;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'dateDepart' => $this->dateDepart,
            'dateArrive' => $this->dateArrive,
            'paiement' => $this->paiement,
            'confirme' => $this->confirme,
            'createdat' => $this->createdat,
            // 'codePromo' => $this->fk_codePromo->toArray(),
            'userNom' => $this->getFkUser()->getNom(),
            'userPrenom' => $this->getFkUser()->getPrenom(),
            'libelle' => $this->getFkLogement()->getLibelle()
            // 'logement' => $this->fk_logement->toArray()
        ];
    }

    public function getCreatedat(): ?string
    {
        return $this->createdat;
    }

    public function setCreatedat(string $createdat): self
    {
        $this->createdat = $createdat;

        return $this;
    }

    public function getFkCodePromo(): ?CodePromo
    {
        return $this->fk_codePromo;
    }

    public function setFkCodePromo(?CodePromo $fk_codePromo): self
    {
        $this->fk_codePromo = $fk_codePromo;

        return $this;
    }

    public function getUserInsolite(): ?string
    {
        return $this->userInsolite;
    }

    public function setUserInsolite(string $userInsolite): self
    {
        $this->userInsolite = $userInsolite;

        return $this;
    }
}
