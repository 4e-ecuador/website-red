<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\WaypointRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WaypointRepository::class)
 *
 * @ApiResource
 */
class Waypoint
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=6)
     */
    private $lat;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=6)
     */
    private $lon;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageLink;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $guid;

    /**
     * @ORM\OneToMany(targetEntity=Key::class, mappedBy="waypoint")
     */
    private $waypointKeys;

    public function __construct()
    {
        $this->waypointKeys = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
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

    public function getLat(): ?string
    {
        return $this->lat;
    }

    public function setLat(string $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLon(): ?string
    {
        return $this->lon;
    }

    public function setLon(string $lon): self
    {
        $this->lon = $lon;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getImageLink(): ?string
    {
        return $this->imageLink;
    }

    public function setImageLink(?string $imageLink): self
    {
        $this->imageLink = $imageLink;

        return $this;
    }

    public function getGuid(): ?string
    {
        return $this->guid;
    }

    public function setGuid(?string $guid): self
    {
        $this->guid = $guid;

        return $this;
    }

    /**
     * @return Collection|Key[]
     */
    public function getWaypointKeys(): Collection
    {
        return $this->waypointKeys;
    }

    public function addWaypointKey(Key $waypointKey): self
    {
        if (!$this->waypointKeys->contains($waypointKey)) {
            $this->waypointKeys[] = $waypointKey;
            $waypointKey->setWaypoint($this);
        }

        return $this;
    }

    public function removeWaypointKey(Key $waypointKey): self
    {
        if ($this->waypointKeys->contains($waypointKey)) {
            $this->waypointKeys->removeElement($waypointKey);
            // set the owning side to null (unless already changed)
            if ($waypointKey->getWaypoint() === $this) {
                $waypointKey->setWaypoint(null);
            }
        }

        return $this;
    }
}
