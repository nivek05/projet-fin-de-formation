<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
     
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $reservation_infoRdv;

    /**
     * @ORM\Column(type="date")
     */
    private $reservation_date;

    /**
     * @ORM\Column(type="integer")
     */
    private $reservation_persQuantity;

    /**
     * @ORM\Column(type="float")
     */
    private $reservation_totalPrice;


    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reservations")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Workshop::class, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $workshop;

    /**
     * @ORM\ManyToOne(targetEntity=Disponibility::class, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $disponibility;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReservationInfoRdv(): ?bool
    {
        return $this->reservation_infoRdv;
    }

    public function setReservationInfoRdv(bool $reservation_infoRdv): self
    {
        $this->reservation_infoRdv = $reservation_infoRdv;

        return $this;
    }

    public function getReservationDate(): ?\DateTimeInterface
    {
        return $this->reservation_date;
    }

    public function setReservationDate(\DateTimeInterface $reservation_date): self
    {
        $this->reservation_date = $reservation_date;

        return $this;
    }

    public function getReservationPersQuantity(): ?int
    {
        return $this->reservation_persQuantity;
    }

    public function setReservationPersQuantity(int $reservation_persQuantity): self
    {
        $this->reservation_persQuantity = $reservation_persQuantity;

        return $this;
    }

    public function getReservationTotalPrice(): ?float
    {
        return $this->reservation_totalPrice;
    }

    public function setReservationTotalPrice(float $reservation_totalPrice): self
    {
        $this->reservation_totalPrice = $reservation_totalPrice;

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

    public function getWorkshop(): ?Workshop
    {
        return $this->workshop;
    }

    public function setWorkshop(?Workshop $workshop): self
    {
        $this->workshop = $workshop;

        return $this;
    }

    public function getDisponibility(): ?Disponibility
    {
        return $this->disponibility;
    }

    public function setDisponibility(?Disponibility $disponibility): self
    {
        $this->disponibility = $disponibility;

        return $this;
    }


}
