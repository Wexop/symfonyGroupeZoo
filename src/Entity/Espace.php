<?php

namespace App\Entity;

use App\Repository\EspaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EspaceRepository::class)]
class Espace
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?int $superficie = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_ouverture = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_fermeture = null;

    #[ORM\OneToMany(mappedBy: 'espace_id', targetEntity: Enclos::class, orphanRemoval: true)]
    private Collection $enclos;

    public function __construct()
    {
        $this->enclos = new ArrayCollection();
    }

    public function __toString() :string {
        return $this->nom;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
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

    public function getDateOuverture(): ?\DateTimeInterface
    {
        return $this->date_ouverture;
    }

    public function setDateOuverture(?\DateTimeInterface $date_ouverture): self
    {
        $this->date_ouverture = $date_ouverture;

        return $this;
    }

    public function getDateFermeture(): ?\DateTimeInterface
    {
        return $this->date_fermeture;
    }

    public function setDateFermeture(?\DateTimeInterface $date_fermeture): self
    {
        $this->date_fermeture = $date_fermeture;

        return $this;
    }

    /**
     * @return Collection<int, Enclos>
     */
    public function getEnclos(): Collection
    {
        return $this->enclos;
    }

    public function addEnclo(Enclos $enclo): self
    {
        if (!$this->enclos->contains($enclo)) {
            $this->enclos->add($enclo);
            $enclo->setEspaceId($this);
        }

        return $this;
    }

    public function removeEnclo(Enclos $enclo): self
    {
        if ($this->enclos->removeElement($enclo)) {
            // set the owning side to null (unless already changed)
            if ($enclo->getEspaceId() === $this) {
                $enclo->setEspaceId(null);
            }
        }

        return $this;
    }
}
