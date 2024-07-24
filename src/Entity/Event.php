<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 * @ORM\Table(name="events")
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Name can't be blank")
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     */
    private $location;

    /**
     * @Assert\PositiveOrZero
     * @ORM\Column(type="decimal", precision=5, scale=2, nullable=true)
     */
    private $price;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=25, minMessage="Description must be at least {{ limit }} characters long")
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @Assert\GreaterThanOrEqual("+1 hour")
     * @ORM\Column(type="datetime")
     */
    private $startsAt;

    /**
     * @Assert\Regex("/^\w+\.(jpe?g|png)$/i", message="Image must be JPG or PNG")
     * @ORM\Column(type="string", length=255, options={"default": "placeholder.jpg"})
     */
    private $imageFileName = 'placeholder.jpg';

    /**
     * @Assert\Positive
     * @ORM\Column(type="integer", options={"default": 1})
     */
    private $capacity = 1;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Registration", mappedBy="event", fetch="EAGER", orphanRemoval=true)
     */
    private $registrations;

    public function __construct()
    {
        $this->registrations = new ArrayCollection();
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

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): self
    {
        $this->price = $price;

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

    public function getStartsAt(): ?\DateTimeInterface
    {
        return $this->startsAt;
    }

    public function setStartsAt(\DateTimeInterface $startsAt): self
    {
        $this->startsAt = $startsAt;

        return $this;
    }

    public function getImageFileName(): ?string
    {
        return $this->imageFileName;
    }

    public function setImageFileName(string $imageFileName): self
    {
        $this->imageFileName = $imageFileName;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

    /**
     * @return Collection|Registration[]
     */
    public function getRegistrations(): Collection
    {
        return $this->registrations;
    }

    public function addRegistration(Registration $registration): self
    {
        if (!$this->registrations->contains($registration)) {
            $this->registrations[] = $registration;
            $registration->setEvent($this);
        }

        return $this;
    }

    public function removeRegistration(Registration $registration): self
    {
        if ($this->registrations->contains($registration)) {
            $this->registrations->removeElement($registration);
            // set the owning side to null (unless already changed)
            if ($registration->getEvent() === $this) {
                $registration->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * Check if an event is free or not.
     * 
     * @return bool
     */
    public function isFree(): bool
    {
        return $this->getPrice() == 0 || is_null($this->getPrice());
    }
    
    /**
     * The number of spots available for this event.
     * 
     * @return int
     */
    public function spotsLeft(): int
    {
        return $this->capacity - $this->registrations->count();
    }

    /**
     * Check if there are no more spots left for this event.
     * 
     * @return bool
     */
    public function isSoldOut(): bool
    {
        return $this->spotsLeft() === 0;
    }
}
