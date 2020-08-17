<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $googleId;

    /**
     * @ORM\OneToMany(targetEntity=Key::class, mappedBy="user")
     */
    private $waypointKeys;

    public function __construct()
    {
        $this->waypointKeys = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->email;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
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
     * @see UserInterface
     */
    public function getPassword()
    {
        // not needed for apps that do not check user passwords
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed for apps that do not check user passwords
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getGoogleId(): ?string
    {
        return $this->googleId;
    }

    public function setGoogleId(?string $googleId): self
    {
        $this->googleId = $googleId;

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
            $waypointKey->setUser($this);
        }

        return $this;
    }

    public function removeWaypointKey(Key $waypointKey): self
    {
        if ($this->waypointKeys->contains($waypointKey)) {
            $this->waypointKeys->removeElement($waypointKey);
            // set the owning side to null (unless already changed)
            if ($waypointKey->getUser() === $this) {
                $waypointKey->setUser(null);
            }
        }

        return $this;
    }
}
