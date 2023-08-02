<?php

namespace App\Entity;

use App\Repository\CategorieDestinationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieDestinationRepository::class)]
class CategorieDestination
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\OneToMany(mappedBy: 'fk_categorie_destination', targetEntity: Logement::class)]
    private Collection $logements;

    public function __construct()
    {
        $this->logements = new ArrayCollection();
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
     * @return Collection<int, Logement>
     */
    public function getLogements(): Collection
    {
        return $this->logements;
    }

    public function addLogement(Logement $logement): self
    {
        if (!$this->logements->contains($logement)) {
            $this->logements->add($logement);
            $logement->setFkCategorieDestination($this);
        }

        return $this;
    }

    public function removeLogement(Logement $logement): self
    {
        if ($this->logements->removeElement($logement)) {
            // set the owning side to null (unless already changed)
            if ($logement->getFkCategorieDestination() === $this) {
                $logement->setFkCategorieDestination(null);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'libelle' => $this->libelle,
        ];
    }
}
