<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContactRepository::class)
 */
class Contact
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
    private $contact_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $contact_email;

    /**
     * @ORM\Column(type="text")
     */
    private $contact_message;

    /**
     * @ORM\Column(type="boolean")
     */
    private $contact_rgpd;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $contact_tel;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $contact_reason;

    /**
     * @ORM\Column(type="date")
     */
    private $contact_date;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="contact")
     */
    private $reservation;

    public function __construct()
    {
        $this->reservation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContactName(): ?string
    {
        return $this->contact_name;
    }

    public function setContactName(string $contact_name): self
    {
        $this->contact_name = $contact_name;

        return $this;
    }

    public function getContactEmail(): ?string
    {
        return $this->contact_email;
    }

    public function setContactEmail(string $contact_email): self
    {
        $this->contact_email = $contact_email;

        return $this;
    }

    public function getContactMessage(): ?string
    {
        return $this->contact_message;
    }

    public function setContactMessage(string $contact_message): self
    {
        $this->contact_message = $contact_message;

        return $this;
    }

    public function getContactRgpd(): ?bool
    {
        return $this->contact_rgpd;
    }

    public function setContactRgpd(bool $contact_rgpd): self
    {
        $this->contact_rgpd = $contact_rgpd;

        return $this;
    }

    public function getContactTel(): ?string
    {
        return $this->contact_tel;
    }

    public function setContactTel(string $contact_tel): self
    {
        $this->contact_tel = $contact_tel;

        return $this;
    }

    public function getContactReason(): ?string
    {
        return $this->contact_reason;
    }

    public function setContactReason(string $contact_reason): self
    {
        $this->contact_reason = $contact_reason;

        return $this;
    }

    public function getContactDate(): ?\DateTimeInterface
    {
        return $this->contact_date;
    }

    public function setContactDate(\DateTimeInterface $contact_date): self
    {
        $this->contact_date = $contact_date;

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservation(): Collection
    {
        return $this->reservation;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservation->contains($reservation)) {
            $this->reservation[] = $reservation;
            $reservation->setContact($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservation->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getContact() === $this) {
                $reservation->setContact(null);
            }
        }

        return $this;
    }
}
