<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    private ?User $fk_user = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    private ?Logement $fk_logement = null;

    #[ORM\Column(length: 255)]
    private ?string $message = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $created = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

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

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'userNom' => $this->getFkUser()->getNom(),
            'userPrenom' => $this->getFkUser()->getPrenom(),
            'userImage' => $this->getFkUser()->getImage(),
            // 'user' => $this->fk_user->toArray(),
            'message' => $this->message,
            'createdAt' => $this->created,
        ];
    }
}
