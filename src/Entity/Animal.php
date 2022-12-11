<?php

namespace App\Entity;

use App\Repository\AnimalRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnimalRepository::class)]
class Animal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $numeroIdentification = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateNaissance = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateArrive = null;

    #[ORM\Column]
    private ?bool $zooProprietaire = null;

    #[ORM\Column(length: 255)]
    private ?string $genre = null;

    #[ORM\Column(length: 255)]
    private ?string $espece = null;

    #[ORM\Column]
    private ?bool $sterile = null;

    #[ORM\Column]
    private ?bool $quarentaine = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroIdentification(): ?int
    {
        return $this->numeroIdentification;
    }

    public function setNumeroIdentification(int $numeroIdentification): self
    {
        $this->numeroIdentification = $numeroIdentification;

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

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(?\DateTimeInterface $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getDateArrive(): ?\DateTimeInterface
    {
        return $this->dateArrive;
    }

    public function setDateArrive(?\DateTimeInterface $dateArrive): self
    {
        $this->dateArrive = $dateArrive;

        return $this;
    }

    public function isZooProprietaire(): ?bool
    {
        return $this->zooProprietaire;
    }

    public function setZooProprietaire(bool $zooProprietaire): self
    {
        $this->zooProprietaire = $zooProprietaire;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getEspece(): ?string
    {
        return $this->espece;
    }

    public function setEspece(string $espece): self
    {
        $this->espece = $espece;

        return $this;
    }

    public function isSterile(): ?bool
    {
        return $this->sterile;
    }

    public function setSterile(bool $sterile): self
    {
        $this->sterile = $sterile;

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
}
