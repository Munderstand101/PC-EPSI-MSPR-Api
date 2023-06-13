<?php

namespace App\Entity;

use App\Repository\GardeningRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GardeningRepository::class)]
#[ORM\Table(name:"cardening_request")]
class Gardening
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'gardenings')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'gardenings')]
    private ?Plante $plant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPlant(): ?Plante
    {
        return $this->plant;
    }

    public function setPlant(?Plante $plant): self
    {
        $this->plant = $plant;

        return $this;
    }
}
