<?php

namespace App\Entity;

use App\Repository\WorkshopRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WorkshopRepository::class)
 */
class Workshop
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $workshop_name;

    /**
     * @ORM\Column(type="float")
     */
    private $workshop_price;

    /**
     * @ORM\Column(type="text")
     */
    private $workshop_desc;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $workshop_img;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="workshop")
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

    public function getWorkshopName(): ?string
    {
        return $this->workshop_name;
    }

    public function setWorkshopName(string $workshop_name): self
    {
        $this->workshop_name = $workshop_name;

        return $this;
    }

    public function getWorkshopPrice(): ?float
    {
        return $this->workshop_price;
    }

    public function setWorkshopPrice(float $workshop_price): self
    {
        $this->workshop_price = $workshop_price;

        return $this;
    }

    public function getWorkshopDesc(): ?string
    {
        return $this->workshop_desc;
    }

    public function setWorkshopDesc(string $workshop_desc): self
    {
        $this->workshop_desc = $workshop_desc;

        return $this;
    }


    public function getWorkshopImg(): ?string
    {
        return $this->workshop_img;
    }

    public function setWorkshopImg(string $workshop_img): self
    {
        $this->workshop_img = $workshop_img;

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
            $reservation->setWorkshop($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getWorkshop() === $this) {
                $reservation->setWorkshop(null);
            }
        }

        return $this;
    }
    

    
}
