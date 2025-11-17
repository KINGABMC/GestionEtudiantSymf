<?php

namespace App\Entity;

use App\Repository\BachelierRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BachelierRepository::class)]
#[ORM\Table(name: 'bacheliers')]
class Bachelier extends User
{
    #[ORM\Column(length: 200)]
    #[Assert\NotBlank(message: 'Le nom complet est requis.')]
    private ?string $nomComplet = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, nullable: true)]
    #[Assert\Range(min: 0, max: 20)]
    private ?string $moyenne = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?TypeBac $typeBac = null;

    #[ORM\Column(nullable: true)]
    private ?int $anneeBac = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $centresInteret = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notesMatieresJson = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getNomComplet(): ?string
    {
        return $this->nomComplet;
    }

    public function setNomComplet(string $nomComplet): static
    {
        $this->nomComplet = $nomComplet;
        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;
        return $this;
    }

    public function getMoyenne(): ?string
    {
        return $this->moyenne;
    }

    public function setMoyenne(?string $moyenne): static
    {
        $this->moyenne = $moyenne;
        return $this;
    }

    public function getTypeBac(): ?TypeBac
    {
        return $this->typeBac;
    }

    public function setTypeBac(?TypeBac $typeBac): static
    {
        $this->typeBac = $typeBac;
        return $this;
    }

    public function getAnneeBac(): ?int
    {
        return $this->anneeBac;
    }

    public function setAnneeBac(?int $anneeBac): static
    {
        $this->anneeBac = $anneeBac;
        return $this;
    }

    public function getCentresInteret(): ?string
    {
        return $this->centresInteret;
    }

    public function setCentresInteret(?string $centresInteret): static
    {
        $this->centresInteret = $centresInteret;
        return $this;
    }

    public function getNotesMatieresJson(): ?string
    {
        return $this->notesMatieresJson;
    }

    public function setNotesMatieresJson(?string $notesMatieresJson): static
    {
        $this->notesMatieresJson = $notesMatieresJson;
        return $this;
    }

    public function getNotesMatieresArray(): array
    {
        if (!$this->notesMatieresJson) {
            return [];
        }
        return json_decode($this->notesMatieresJson, true) ?? [];
    }

    public function setNotesMatieresArray(array $notes): static
    {
        $this->notesMatieresJson = json_encode($notes);
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
