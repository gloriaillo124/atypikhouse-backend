<?php

namespace App\Entity;

use App\Repository\LogementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LogementRepository::class)]
class Logement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image3 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image4 = null;

    #[ORM\Column(length: 255)]
    private ?string $montant = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $capaciteAccueil = null;

    #[ORM\Column(length: 255)]
    private ?string $disponible = null;

    #[ORM\Column(length: 255)]
    private ?string $nombrePiece = null;

    #[ORM\Column(length: 255)]
    private ?string $nombreChambre = null;

    #[ORM\ManyToOne(inversedBy: 'logements')]
    private ?CategorieHebergement $fk_categorie_hebergement = null;

    #[ORM\ManyToOne(inversedBy: 'logements')]
    private ?CategorieDestination $fk_categorie_destination = null;

    #[ORM\ManyToOne(inversedBy: 'logements')]
    private ?CategorieInspiration $fk_categorie_inspiration = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $created = null;

    #[ORM\OneToMany(mappedBy: 'fk_logement', targetEntity: LogementOption::class)]
    private Collection $logementOptions;

    #[ORM\OneToMany(mappedBy: 'fk_logement', targetEntity: Commentaire::class)]
    private Collection $commentaires;

    #[ORM\OneToMany(mappedBy: 'fk_logement', targetEntity: Reservation::class)]
    private Collection $reservations;

    #[ORM\OneToMany(mappedBy: 'fk_logement', targetEntity: CodePromo::class)]
    private Collection $codePromos;

    #[ORM\ManyToOne(inversedBy: 'logements')]
    private ?User $fk_user = null;

    #[ORM\Column(length: 255)]
    private ?string $statut = "0";

    #[ORM\OneToMany(mappedBy: 'fk_logement', targetEntity: Avis::class)]
    private Collection $avis;

    #[ORM\Column]
    private ?int $hebergementId = null;

    #[ORM\Column]
    private ?int $destinationId = null;

    #[ORM\Column]
    private ?int $inspirationId = null;

    #[ORM\Column]
    private ?int $userId = null;

    #[ORM\OneToMany(mappedBy: 'logement', targetEntity: Option::class)]
    private Collection $fk_option;

    public function __construct()
    {
        $this->logementOptions = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->codePromos = new ArrayCollection();
        $this->avis = new ArrayCollection();
        $this->fk_option = new ArrayCollection();
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

    public function getImage1(): ?string
    {
        return $this->image1;
    }

    public function setImage1(string $image1): self
    {
        $this->image1 = $image1;

        return $this;
    }

    public function getImage2(): ?string
    {
        return $this->image2;
    }

    public function setImage2(string $image2): self
    {
        $this->image2 = $image2;

        return $this;
    }

    public function getImage3(): ?string
    {
        return $this->image3;
    }

    public function setImage3(string $image3): self
    {
        $this->image3 = $image3;

        return $this;
    }

    public function getImage4(): ?string
    {
        return $this->image4;
    }

    public function setImage4(string $image4): self
    {
        $this->image4 = $image4;

        return $this;
    }

    public function getMontant(): ?string
    {
        return $this->montant;
    }

    public function setMontant(string $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCapaciteAccueil(): ?string
    {
        return $this->capaciteAccueil;
    }

    public function setCapaciteAccueil(string $capaciteAccueil): self
    {
        $this->capaciteAccueil = $capaciteAccueil;

        return $this;
    }

    public function getDisponible(): ?string
    {
        return $this->disponible;
    }

    public function setDisponible(string $disponible): self
    {
        $this->disponible = $disponible;

        return $this;
    }

    public function getNombrePiece(): ?string
    {
        return $this->nombrePiece;
    }

    public function setNombrePiece(string $nombrePiece): self
    {
        $this->nombrePiece = $nombrePiece;

        return $this;
    }

    public function getNombreChambre(): ?string
    {
        return $this->nombreChambre;
    }

    public function setNombreChambre(string $nombreChambre): self
    {
        $this->nombreChambre = $nombreChambre;

        return $this;
    }

    public function getFkCategorieHebergement(): ?CategorieHebergement
    {
        return $this->fk_categorie_hebergement;
    }

    public function setFkCategorieHebergement(?CategorieHebergement $fk_categorie_hebergement): self
    {
        $this->fk_categorie_hebergement = $fk_categorie_hebergement;

        return $this;
    }

    public function getFkCategorieDestination(): ?CategorieDestination
    {
        return $this->fk_categorie_destination;
    }

    public function setFkCategorieDestination(?CategorieDestination $fk_categorie_destination): self
    {
        $this->fk_categorie_destination = $fk_categorie_destination;

        return $this;
    }

    public function getFkCategorieInspiration(): ?CategorieInspiration
    {
        return $this->fk_categorie_inspiration;
    }

    public function setFkCategorieInspiration(?CategorieInspiration $fk_categorie_inspiration): self
    {
        $this->fk_categorie_inspiration = $fk_categorie_inspiration;

        return $this;
    }

    public function getCreated(): ?string
    {
        return $this->created;
    }

    public function setCreated(?string $created): self
    {
        $this->created = $created;

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
            $logementOption->setFkLogement($this);
        }

        return $this;
    }

    public function removeLogementOption(LogementOption $logementOption): self
    {
        if ($this->logementOptions->removeElement($logementOption)) {
            // set the owning side to null (unless already changed)
            if ($logementOption->getFkLogement() === $this) {
                $logementOption->setFkLogement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setFkLogement($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getFkLogement() === $this) {
                $commentaire->setFkLogement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setFkLogement($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getFkLogement() === $this) {
                $reservation->setFkLogement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CodePromo>
     */
    public function getCodePromos(): Collection
    {
        return $this->codePromos;
    }

    public function addCodePromo(CodePromo $codePromo): self
    {
        if (!$this->codePromos->contains($codePromo)) {
            $this->codePromos->add($codePromo);
            $codePromo->setFkLogement($this);
        }

        return $this;
    }

    public function removeCodePromo(CodePromo $codePromo): self
    {
        if ($this->codePromos->removeElement($codePromo)) {
            // set the owning side to null (unless already changed)
            if ($codePromo->getFkLogement() === $this) {
                $codePromo->setFkLogement(null);
            }
        }

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

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'libelle' => $this->libelle,
            'image1' => $this->image1,
            'image2' => $this->image2,
            'image3' => $this->image3,
            'image4' => $this->image4,
            'montant' => $this->montant,
            'description' => $this->description,
            'capaciteAccueil' => $this->capaciteAccueil,
            'disponible' => $this->disponible,
            'nombrePiece' => $this->nombrePiece,
            'nombreChambre' => $this->nombreChambre,
            'hebergementId' => $this->hebergementId,
            'destinationId' => $this->destinationId,
            'inspirationId' => $this->inspirationId,
            'hebergementLibelle' => $this->getFkCategorieHebergement()->getLibelle(),
            'hebergementDescription' => $this->getFkCategorieHebergement()->getDescription(),
            'destinationLibelle' => $this->getFkCategorieDestination()->getLibelle(),
            'inspirationLibelle' => $this->getFkCategorieInspiration()->getLibelle(),
            'userId' => $this->userId,
            'statut' => $this->statut,
            'created' => $this->created,

        ];
    }

    /**
     * @return Collection<int, Avis>
     */
    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function addAvi(Avis $avi): self
    {
        if (!$this->avis->contains($avi)) {
            $this->avis->add($avi);
            $avi->setFkLogement($this);
        }

        return $this;
    }

    public function removeAvi(Avis $avi): self
    {
        if ($this->avis->removeElement($avi)) {
            // set the owning side to null (unless already changed)
            if ($avi->getFkLogement() === $this) {
                $avi->setFkLogement(null);
            }
        }

        return $this;
    }

    public function getHebergementId(): ?int
    {
        return $this->hebergementId;
    }

    public function setHebergementId(int $hebergementId): self
    {
        $this->hebergementId = $hebergementId;

        return $this;
    }

    public function getDestinationId(): ?int
    {
        return $this->destinationId;
    }

    public function setDestinationId(int $destinationId): self
    {
        $this->destinationId = $destinationId;

        return $this;
    }

    public function getInspirationId(): ?int
    {
        return $this->inspirationId;
    }

    public function setInspirationId(int $inspirationId): self
    {
        $this->inspirationId = $inspirationId;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return Collection<int, Option>
     */
    public function getFkOption(): Collection
    {
        return $this->fk_option;
    }

    public function addFkOption(Option $fkOption): self
    {
        if (!$this->fk_option->contains($fkOption)) {
            $this->fk_option->add($fkOption);
            $fkOption->setLogement($this);
        }

        return $this;
    }

    public function removeFkOption(Option $fkOption): self
    {
        if ($this->fk_option->removeElement($fkOption)) {
            // set the owning side to null (unless already changed)
            if ($fkOption->getLogement() === $this) {
                $fkOption->setLogement(null);
            }
        }

        return $this;
    }

}
