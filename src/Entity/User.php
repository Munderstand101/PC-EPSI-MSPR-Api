<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("read")]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups("read")]
    private ?string $email = null;

    #[ORM\Column(type: 'string', length: 180)]
    #[Groups("read")]
    private $username;
 
    #[ORM\Column]
   // #[Groups("read")]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;


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

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Gardening::class)]
    private Collection $gardenings;

    #[ORM\Column(length: 255)]
    #[Groups("read")]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Groups("read")]
    private ?string $lastName = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Plante::class)]
    private Collection $plantes;

    public function __construct()
    {
        $this->gardenings = new ArrayCollection();
        $this->plantes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): string
    {
        return (string) $this->username;
    }
 
    public function setUsername(string $username): self
    {
        $this->username = $username;
 
        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string|null $address
     */
    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return string|null
     */
    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    /**
     * @param string|null $zipcode
     */
    public function setZipcode(?string $zipcode): void
    {
        $this->zipcode = $zipcode;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string|null $city
     */
    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return float|null
     */
    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    /**
     * @param float|null $longitude
     */
    public function setLongitude(?float $longitude): void
    {
        $this->longitude = $longitude;
    }

    /**
     * @return float|null
     */
    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    /**
     * @param float|null $latitude
     */
    public function setLatitude(?float $latitude): void
    {
        $this->latitude = $latitude;
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
            $gardening->setUser($this);
        }

        return $this;
    }

    public function removeGardening(Gardening $gardening): self
    {
        if ($this->gardenings->removeElement($gardening)) {
            // set the owning side to null (unless already changed)
            if ($gardening->getUser() === $this) {
                $gardening->setUser(null);
            }
        }

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return Collection<int, Plante>
     */
    public function getPlantes(): Collection
    {
        return $this->plantes;
    }

    public function addPlante(Plante $plante): self
    {
        if (!$this->plantes->contains($plante)) {
            $this->plantes->add($plante);
            $plante->setUser($this);
        }

        return $this;
    }

    public function removePlante(Plante $plante): self
    {
        if ($this->plantes->removeElement($plante)) {
            // set the owning side to null (unless already changed)
            if ($plante->getUser() === $this) {
                $plante->setUser(null);
            }
        }

        return $this;
    }




}
