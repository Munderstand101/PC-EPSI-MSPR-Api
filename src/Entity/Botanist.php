<?php

namespace App\Entity;

use App\Repository\BotanistRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BotanistRepository::class)]
class Botanist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("read")]
    private ?int $id = null;

//    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
////    #[Groups("read")]
//    private ?User $user = null;

    #[ORM\Column(length: 255)]
    #[Groups("read")]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups("read")]
    private ?string $specialization = null;

    #[ORM\Column(length: 255)]
    #[Groups("read")]
    private ?string $address = null;

    #[ORM\Column(length: 255)]
    #[Groups("read")]
    private ?string $zipcode = null;

    #[ORM\Column(length: 255)]
    #[Groups("read")]
    private ?string $city = null;

    #[ORM\Column]
    #[Groups("read")]
    private ?float $longitude = null;

    #[ORM\Column]
    #[Groups("read")]
    private ?float $latitude = null;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSpecialization(): ?string
    {
        return $this->specialization;
    }

    public function setSpecialization(string $specialization): self
    {
        $this->specialization = $specialization;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }
}
