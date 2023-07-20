<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;
use Symfony\Component\Serializer\Annotation\Groups;


trait PeriodTrait
{
    #[ORM\Column(type: 'datetime')]
    #[Groups("read")]
    private $startDate;

    #[ORM\Column(type: 'datetime')]
    #[Groups("read")]
    private $endDate;

    /**
     * @return DateTimeInterface|null
     */
    public function getStartDate(): ?DateTimeInterface
    {
        return $this->startDate;
    }

    /**
     * @param DateTimeInterface|null $startDate
     * @return self
     */
    public function setStartDate(?DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getEndDate(): ?DateTimeInterface
    {
        return $this->endDate;
    }

    /**
     * @param DateTimeInterface|null $endDate
     * @return self
     */
    public function setEndDate(?DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasPeriod(): bool
    {
        return $this->startDate !== null && $this->endDate !== null;
    }

    /**
     * @return bool
     */
    public function isPeriodValid(): bool
    {
        return $this->hasPeriod() && $this->startDate <= $this->endDate;
    }

    /*
    #[ORM\PrePersist]
    public function setTimestampsOnCreate(): void
    {
        $this->startDate = new \DateTimeImmutable();
        $this->endDate = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function setTimestampsOnUpdate(): void
    {
        $this->endDate = new \DateTimeImmutable();
    }*/
}
