<?php

namespace App\Entity;

use App\Entity\Traits\TimestampsTrait;
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
    use TimestampsTrait;
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

    #[ORM\Column(nullable: true)]
    #[Groups("read")]
    private ?float $longitude = null;

    #[ORM\Column(nullable: true)]
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

    #[ORM\Column(length: 255)]
    #[Groups("read")]
    private ?string $picture_url = null;

    #[ORM\OneToMany(mappedBy: 'sender', targetEntity: Message::class)]
    private Collection $sentMessages;

    #[ORM\OneToMany(mappedBy: 'recipient', targetEntity: Message::class)]
    private Collection $receivedMessages;

    #[ORM\OneToMany(mappedBy: 'startedBy', targetEntity: Conversation::class)]
    private Collection $receivedConv;

    #[ORM\OneToMany(mappedBy: 'targetUser', targetEntity: Conversation::class)]
    private Collection $conversations;

    #[Groups("read")]
    private $fullName;

    /**
     * @return mixed
     */
    public function getFullName(): string
    {
        return $this->firstName . " " . $this->lastName;
    }



    public function __construct()
    {
        $this->gardenings = new ArrayCollection();
        $this->plantes = new ArrayCollection();
        $this->sentMessages = new ArrayCollection();
        $this->receivedMessages = new ArrayCollection();
        $this->receivedConv = new ArrayCollection();
        $this->conversations = new ArrayCollection();

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

    public function getPictureUrl(): ?string
    {
        return $this->picture_url;
    }

    public function setPictureUrl(string $picture_url): static
    {
        $this->picture_url = $picture_url;

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getSentMessages(): Collection
    {
        return $this->sentMessages;
    }

    public function addSentMessage(Message $sentMessage): static
    {
        if (!$this->sentMessages->contains($sentMessage)) {
            $this->sentMessages->add($sentMessage);
            $sentMessage->setSender($this);
        }

        return $this;
    }

    public function removeSentMessage(Message $sentMessage): static
    {
        if ($this->sentMessages->removeElement($sentMessage)) {
            // set the owning side to null (unless already changed)
            if ($sentMessage->getSender() === $this) {
                $sentMessage->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getReceivedMessages(): Collection
    {
        return $this->receivedMessages;
    }

    public function addReceivedMessage(Message $receivedMessage): static
    {
        if (!$this->receivedMessages->contains($receivedMessage)) {
            $this->receivedMessages->add($receivedMessage);
            $receivedMessage->setRecipient($this);
        }

        return $this;
    }

    public function removeReceivedMessage(Message $receivedMessage): static
    {
        if ($this->receivedMessages->removeElement($receivedMessage)) {
            // set the owning side to null (unless already changed)
            if ($receivedMessage->getRecipient() === $this) {
                $receivedMessage->setRecipient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Conversation>
     */
    public function getReceivedConv(): Collection
    {
        return $this->receivedConv;
    }

    public function addReceivedConv(Conversation $receivedConv): static
    {
        if (!$this->receivedConv->contains($receivedConv)) {
            $this->receivedConv->add($receivedConv);
            $receivedConv->setStartedBy($this);
        }

        return $this;
    }

    public function removeReceivedConv(Conversation $receivedConv): static
    {
        if ($this->receivedConv->removeElement($receivedConv)) {
            // set the owning side to null (unless already changed)
            if ($receivedConv->getStartedBy() === $this) {
                $receivedConv->setStartedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Conversation>
     */
    public function getConversations(): Collection
    {
        return $this->conversations;
    }

    public function addConversation(Conversation $conversation): static
    {
        if (!$this->conversations->contains($conversation)) {
            $this->conversations->add($conversation);
            $conversation->setTargetUser($this);
        }

        return $this;
    }

    public function removeConversation(Conversation $conversation): static
    {
        if ($this->conversations->removeElement($conversation)) {
            // set the owning side to null (unless already changed)
            if ($conversation->getTargetUser() === $this) {
                $conversation->setTargetUser(null);
            }
        }

        return $this;
    }




}
