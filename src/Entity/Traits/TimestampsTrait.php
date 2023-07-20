<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait TimestampsTrait
{
    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups("read")]
    private $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups("read")]
    private $updatedAt;

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    #[ORM\PrePersist]
    public function setTimestampsOnCreate(): void
    {
        // Ensure valid datetime values using DateTimeImmutable
        $now = new \DateTimeImmutable();
        $this->createdAt = $now;
        $this->updatedAt = $now;
    }

    #[ORM\PreUpdate]
    public function setTimestampsOnUpdate(): void
    {
        // Ensure valid datetime values using DateTimeImmutable
        $this->updatedAt = new \DateTimeImmutable();
    }
}
