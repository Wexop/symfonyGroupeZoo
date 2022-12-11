<?php

namespace App\Entity;

use App\Repository\TestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TestRepository::class)]
class Test
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $testint = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTestint(): ?string
    {
        return $this->testint;
    }

    public function setTestint(string $testint): self
    {
        $this->testint = $testint;

        return $this;
    }
}
