<?php

namespace App\Entity;

use App\Repository\LogementOptionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LogementOptionRepository::class)]
class LogementOption
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $optionValeur = null;

    #[ORM\ManyToOne(inversedBy: 'logementOptions')]
    private ?Logement $fk_logement = null;

    #[ORM\ManyToOne(inversedBy: 'logementOptions')]
    private ?Option $fk_option = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOptionValeur(): ?string
    {
        return $this->optionValeur;
    }

    public function setOptionValeur(string $optionValeur): self
    {
        $this->optionValeur = $optionValeur;

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

    public function getFkOption(): ?Option
    {
        return $this->fk_option;
    }

    public function setFkOption(?Option $fk_option): self
    {
        $this->fk_option = $fk_option;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'libelle' => $this->getFkOption()->getLibelle(),
            'optionValeur' => $this->optionValeur
        ];
    }
}
