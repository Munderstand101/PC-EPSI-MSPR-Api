<?php

namespace App\Entity;

use App\Repository\PlanteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PlanteRepository::class)]
class Plante
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("read")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups("read")]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups("read")]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'plant', targetEntity: Gardening::class)]
   // #[Groups("read")]
    private Collection $gardenings;

    #[ORM\Column(length: 255)]
    #[Groups("read")]
    private ?string $photo = null;

    #[ORM\ManyToOne(inversedBy: 'plantes')]
    private ?User $user = null;

    public function __construct()
    {
        $this->gardenings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Gardening>
     */
    public function getGardenings(): Collection
    {
        return $this->gardenings;
    }

    public function addGardening(Gardening $gardening): self
    {
        if (!$this->gardenings->contains($gardening)) {
            $this->gardenings->add($gardening);
            $gardening->setPlant($this);
        }

        return $this;
    }

    public function removeGardening(Gardening $gardening): self
    {
        if ($this->gardenings->removeElement($gardening)) {
            // set the owning side to null (unless already changed)
            if ($gardening->getPlant() === $this) {
                $gardening->setPlant(null);
            }
        }

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
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
}
