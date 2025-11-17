<?php

namespace App\Entity;

use App\Repository\EmployeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;

#[ORM\Entity(repositoryClass: EmployeRepository::class)]
#[UniqueEntity(fields: ['telephone'], message: 'Ce numéro de téléphone est déjà utilisé.')]
class Employe extends User
{


    #[ORM\Column(length: 200)]
    #[Assert\NotBlank(message: 'Le nom et le prénom  de l\'employé est requis.')]
    #[Assert\Length(max: 200, min: 2, minMessage: 'Le nom et le prénom de l\'employé doit avoir au moins {{ limit }} caractères.', maxMessage: 'Le nom et le prénom de l\'employé ne peut pas dépasser {{ limit }} caractères.')]
    private ?string $nomComplet = null;

    #[ORM\Column(length: 25, unique: true)]
    #[Assert\NotBlank(message: 'Le numéro de téléphone est requis.')]
#[Assert\Regex(
    pattern: "/^(77|78)\d{7}$/",
    message: "Le numéro de téléphone '{{ value }}' n'est pas valide. Il doit commencer par 77 ou 78 et contenir 9 chiffres au total."
)]
    private ?string $telephone = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\NotBlank(message: 'L\'adresse est requise.')] 
    private ?string $adresse = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updateAt = null;

    #[ORM\ManyToOne(inversedBy: 'employes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'Le département est requis.')]
    private ?Departement $departement = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotNull(message: 'La date d\'embauche est requise.')]
    #[Assert\LessThanOrEqual('today', message: 'La date d\'embauche ne peut pas être superieure à  aujourd\'hui.')]
    private ?\DateTimeImmutable $embaucheAt = null;


    #[ORM\Column(type: 'boolean')]
    private ?bool $isArchived = null;
// nom de l'image
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;
    //champ du tampon non mappé à la base de données
    #[Assert\Image(
        maxSize: "2M",
        mimeTypes: ["image/jpeg", "image/png", "image/jpg"],
        mimeTypesMessage: "Veuillez télécharger une image valide (JPEG ou PNG)."
    )]
    private $photoFile;

    public function __construct()
    {   
        $this->createAt = new \DateTimeImmutable();
        $this->isArchived = false;
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

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;
        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;
        return $this;
    }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeImmutable $createAt): static
    {
        $this->createAt = $createAt;
        return $this;
    }

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->updateAt;
    }

    public function setUpdateAt(?\DateTimeImmutable $updateAt): static
    {
        $this->updateAt = $updateAt;
        return $this;
    }

    public function getDepartement(): ?Departement
    {
        return $this->departement;
    }

    public function setDepartement(?Departement $departement): static
    {
        $this->departement = $departement;
        return $this;
    }

    public function getEmbaucheAt(): ?\DateTimeImmutable
    {
        return $this->embaucheAt;
    }

    public function setEmbaucheAt(?\DateTimeImmutable $embaucheAt): static
    {
        $this->embaucheAt = $embaucheAt;
        return $this;
    }

    public function getisArchived(): ?bool
    {
        return $this->isArchived;
    }

    public function setIsArchived(bool $isArchived): static
    {
        $this->isArchived = $isArchived;
        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }
    public function getPhotoFile()
    {
        return $this->photoFile;
    }
    public function setPhotoFile($photoFile): static
    {
        $this->photoFile = $photoFile;

        return $this;
    }
}
