<?php

namespace App\Entity;

use App\Repository\DisponibilityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DisponibilityRepository::class)
 */
class Disponibility
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $disponibility_date;

    /**
     * @ORM\Column(type="boolean")
     */
    private $disponibility_isDispo;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="disponibility")
     */
    private $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDisponibilityDate(): ?\DateTimeInterface
    {
        return $this->disponibility_date;
    }

    public function setDisponibilityDate(\DateTimeInterface $dispponibility_date): self
    {
        $this->disponibility_date = $dispponibility_date;

        return $this;
    }

    public function getDisponibilityIsDispo(): ?bool
    {
        return $this->disponibility_isDispo;
    }

    public function setDisponibilityIsDispo(bool $disponibility_isDispo): self
    {
        $this->disponibility_isDispo = $disponibility_isDispo;

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setDisponibility($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getDisponibility() === $this) {
                $reservation->setDisponibility(null);
            }
        }

        return $this;
    }
    
}
