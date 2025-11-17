<?php

namespace App\Entity;

use App\Repository\DepartementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: DepartementRepository::class)]
#[ORM\Table(name: 'departements')]
#[UniqueEntity(fields: ['name'], message: 'Un département avec ce nom existe déjà.')] 
class Departement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    /*regle de validation
        name unique
        name pas vide
    */
    #[Assert\NotBlank (message: 'Le nom du département ne peut pas être vide.')]
    #[Assert\Length(
       min: 2,
        minMessage: 'Le nom du département doit contenir au moins {{ limit }} caractères.',
        max: 100,
        maxMessage: 'Le nom du département ne peut pas dépasser {{ limit }} caractères.'
    )]
    #[ORM\Column(length: 100, unique: true)]
    private ?string $name = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createAt = null;

    #[ORM\Column (nullable: true)]
    private ?\DateTimeImmutable $updateAT = null;

    #[ORM\Column()]
    private ?bool $isArchived = null;

    /**
     * @var Collection<int, Employe>
     */
    #[ORM\OneToMany(targetEntity: Employe::class, mappedBy: 'departement')]
    private Collection $employes;

    public function __construct()
    {
        $this->employes = new ArrayCollection();
        $this->createAt = new \DateTimeImmutable();
         $this->updateAT = new \DateTimeImmutable();
        $this->isArchived = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    public function getUpdateAT(): ?\DateTimeImmutable
    {
        return $this->updateAT;
    }

    public function setUpdateAT(\DateTimeImmutable $updateAT): static
    {
        $this->updateAT = $updateAT;

        return $this;
    }

    public function getIsArchived(): ?bool
    {
        return $this->isArchived;
    }

    public function setIsArchived(bool $isArchived): static
    {
        $this->isArchived = $isArchived;

        return $this;
    }

    /**
     * @return Collection<int, Employe>
     */
    public function getEmployes(): Collection
    {
        return $this->employes;
    }

    public function addEmploye(Employe $employe): static
    {
        if (!$this->employes->contains($employe)) {
            $this->employes->add($employe);
            $employe->setDepartement($this);
        }

        return $this;
    }

    public function removeEmploye(Employe $employe): static
    {
        if ($this->employes->removeElement($employe)) {
            // set the owning side to null (unless already changed)
            if ($employe->getDepartement() === $this) {
                $employe->setDepartement(null);
            }
        }

        return $this;
    }
}
