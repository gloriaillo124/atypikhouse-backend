<?php

namespace App\Entity;

use App\Repository\OptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OptionRepository::class)]
#[ORM\Table(name: '`option`')]
class Option
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\OneToMany(mappedBy: 'fk_option', targetEntity: LogementOption::class)]
    private Collection $logementOptions;

    #[ORM\ManyToOne(inversedBy: 'fk_option')]
    private ?Logement $logement = null;

    public function __construct()
    {
        $this->logementOptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection<int, LogementOption>
     */
    public function getLogementOptions(): Collection
    {
        return $this->logementOptions;
    }

    public function addLogementOption(LogementOption $logementOption): self
    {
        if (!$this->logementOptions->contains($logementOption)) {
            $this->logementOptions->add($logementOption);
            $logementOption->setFkOption($this);
        }

        return $this;
    }

    public function removeLogementOption(LogementOption $logementOption): self
    {
        if ($this->logementOptions->removeElement($logementOption)) {
            // set the owning side to null (unless already changed)
            if ($logementOption->getFkOption() === $this) {
                $logementOption->setFkOption(null);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'libelle' => $this->libelle
        ];
    }

    public function getLogement(): ?Logement
    {
        return $this->logement;
    }

    public function setLogement(?Logement $logement): self
    {
        $this->logement = $logement;

        return $this;
    }
}
