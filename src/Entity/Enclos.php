<?php

namespace App\Entity;

use App\Repository\EnclosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EnclosRepository::class)]
class Enclos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'enclos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Espace $espace_id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?int $superficie = null;

    #[ORM\Column]
    private ?int $maxAnimaux = null;

    #[ORM\Column]
    private ?bool $quarentaine = null;

    #[ORM\OneToMany(mappedBy: 'enclos', targetEntity: Animal::class, orphanRemoval: true)]
    private Collection $animaux;

    public function __construct()
    {
        $this->animaux = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEspaceId(): ?Espace
    {
        return $this->espace_id;
    }

    public function setEspaceId(?Espace $espace_id): self
    {
        $this->espace_id = $espace_id;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getSuperficie(): ?int
    {
        return $this->superficie;
    }

    public function setSuperficie(int $superficie): self
    {
        $this->superficie = $superficie;

        return $this;
    }

    public function getMaxAnimaux(): ?int
    {
        return $this->maxAnimaux;
    }

    public function setMaxAnimaux(int $maxAnimaux): self
    {
        $this->maxAnimaux = $maxAnimaux;

        return $this;
    }

    public function isQuarentaine(): ?bool
    {
        return $this->quarentaine;
    }

    public function setQuarentaine(bool $quarentaine): self
    {
        $this->quarentaine = $quarentaine;

        return $this;
    }

    /**
     * @return Collection<int, Animal>
     */
    public function getAnimaux(): Collection
    {
        return $this->animaux;
    }

    public function addAnimaux(Animal $animaux): self
    {
        if (!$this->animaux->contains($animaux)) {
            $this->animaux->add($animaux);
            $animaux->setEnclos($this);
        }

        return $this;
    }

    public function removeAnimaux(Animal $animaux): self
    {
        if ($this->animaux->removeElement($animaux)) {
            // set the owning side to null (unless already changed)
            if ($animaux->getEnclos() === $this) {
                $animaux->setEnclos(null);
            }
        }

        return $this;
    }
}
