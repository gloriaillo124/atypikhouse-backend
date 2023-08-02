<?php

namespace App\Entity;

use App\Repository\AvisRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AvisRepository::class)]
class Avis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $valeurEtoile = null;

    #[ORM\ManyToOne(inversedBy: 'avis')]
    private ?User $fk_user = null;

    #[ORM\ManyToOne(inversedBy: 'avis')]
    private ?Logement $fk_logement = null;

    #[ORM\Column(length: 255)]
    private ?string $createdat = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValeurEtoile(): ?string
    {
        return $this->valeurEtoile;
    }

    public function setValeurEtoile(string $valeurEtoile): self
    {
        $this->valeurEtoile = $valeurEtoile;

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

    public function getFkLogement(): ?Logement
    {
        return $this->fk_logement;
    }

    public function setFkLogement(?Logement $fk_logement): self
    {
        $this->fk_logement = $fk_logement;

        return $this;
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

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'valeurEtoile' => $this->valeurEtoile,
        ];
    }
}
