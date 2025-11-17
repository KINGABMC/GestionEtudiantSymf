<?php

namespace App\Entity;

use App\Repository\FiliereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FiliereRepository::class)]
#[ORM\Table(name: 'filieres')]
class Filiere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le nom de la filière est requis.')]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, nullable: true)]
    #[Assert\Range(min: 0, max: 20)]
    private ?string $moyenneMinimale = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $dureeAnnees = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $coutAnnuel = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $debouches = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $documentsRequis = null;

    #[ORM\Column]
    private ?bool $concoursObligatoire = false;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $matieresImportantes = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $diplomeDelivre = null;

    #[ORM\Column]
    private ?bool $estActive = true;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'filieres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ecole $ecole = null;

    #[ORM\ManyToMany(targetEntity: TypeBac::class, inversedBy: 'filieres')]
    #[ORM\JoinTable(name: 'filiere_type_bac')]
    private Collection $typeBacsAcceptes;

    #[ORM\OneToMany(targetEntity: Avis::class, mappedBy: 'filiere', cascade: ['persist', 'remove'])]
    private Collection $avis;

    public function __construct()
    {
        $this->typeBacsAcceptes = new ArrayCollection();
        $this->avis = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->estActive = true;
        $this->concoursObligatoire = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getMoyenneMinimale(): ?string
    {
        return $this->moyenneMinimale;
    }

    public function setMoyenneMinimale(?string $moyenneMinimale): static
    {
        $this->moyenneMinimale = $moyenneMinimale;
        return $this;
    }

    public function getDureeAnnees(): ?int
    {
        return $this->dureeAnnees;
    }

    public function setDureeAnnees(?int $dureeAnnees): static
    {
        $this->dureeAnnees = $dureeAnnees;
        return $this;
    }

    public function getCoutAnnuel(): ?string
    {
        return $this->coutAnnuel;
    }

    public function setCoutAnnuel(?string $coutAnnuel): static
    {
        $this->coutAnnuel = $coutAnnuel;
        return $this;
    }

    public function getDebouches(): ?string
    {
        return $this->debouches;
    }

    public function setDebouches(?string $debouches): static
    {
        $this->debouches = $debouches;
        return $this;
    }

    public function getDocumentsRequis(): ?string
    {
        return $this->documentsRequis;
    }

    public function setDocumentsRequis(?string $documentsRequis): static
    {
        $this->documentsRequis = $documentsRequis;
        return $this;
    }

    public function isConcoursObligatoire(): ?bool
    {
        return $this->concoursObligatoire;
    }

    public function setConcoursObligatoire(bool $concoursObligatoire): static
    {
        $this->concoursObligatoire = $concoursObligatoire;
        return $this;
    }

    public function getMatieresImportantes(): ?string
    {
        return $this->matieresImportantes;
    }

    public function setMatieresImportantes(?string $matieresImportantes): static
    {
        $this->matieresImportantes = $matieresImportantes;
        return $this;
    }

    public function getDiplomeDelivre(): ?string
    {
        return $this->diplomeDelivre;
    }

    public function setDiplomeDelivre(?string $diplomeDelivre): static
    {
        $this->diplomeDelivre = $diplomeDelivre;
        return $this;
    }

    public function isEstActive(): ?bool
    {
        return $this->estActive;
    }

    public function setEstActive(bool $estActive): static
    {
        $this->estActive = $estActive;
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

    public function getEcole(): ?Ecole
    {
        return $this->ecole;
    }

    public function setEcole(?Ecole $ecole): static
    {
        $this->ecole = $ecole;
        return $this;
    }

    public function getTypeBacsAcceptes(): Collection
    {
        return $this->typeBacsAcceptes;
    }

    public function addTypeBacsAccepte(TypeBac $typeBacsAccepte): static
    {
        if (!$this->typeBacsAcceptes->contains($typeBacsAccepte)) {
            $this->typeBacsAcceptes->add($typeBacsAccepte);
        }
        return $this;
    }

    public function removeTypeBacsAccepte(TypeBac $typeBacsAccepte): static
    {
        $this->typeBacsAcceptes->removeElement($typeBacsAccepte);
        return $this;
    }

    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function addAvi(Avis $avi): static
    {
        if (!$this->avis->contains($avi)) {
            $this->avis->add($avi);
            $avi->setFiliere($this);
        }
        return $this;
    }

    public function removeAvi(Avis $avi): static
    {
        if ($this->avis->removeElement($avi)) {
            if ($avi->getFiliere() === $this) {
                $avi->setFiliere(null);
            }
        }
        return $this;
    }

    public function getMoyenneAvis(): float
    {
        if ($this->avis->isEmpty()) {
            return 0;
        }
        $total = 0;
        foreach ($this->avis as $avis) {
            $total += $avis->getNote();
        }
        return round($total / $this->avis->count(), 1);
    }
}
