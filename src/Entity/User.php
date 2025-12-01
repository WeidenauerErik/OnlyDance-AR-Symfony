<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\ManyToMany(targetEntity: DanceSchool::class, mappedBy: 'allowedUser')]
    private Collection $danceSchools;

    #[ORM\ManyToMany(targetEntity: DanceSchool::class, mappedBy: 'allowedAdminUser')]
    private Collection $adminDanceSchools;

    public function __construct()
    {
        $this->dances = new ArrayCollection();
        $this->danceSchools = new ArrayCollection();
        $this->adminDanceSchools = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

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

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0".self::class."\0password"] = hash('crc32c', $this->password);

        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
    }

    /**
     * @return Collection<int, DanceSchool>
     */
    public function getDanceSchools(): Collection
    {
        return $this->danceSchools;
    }

    public function addDanceSchool(DanceSchool $danceSchool): static
    {
        if (!$this->danceSchools->contains($danceSchool)) {
            $this->danceSchools->add($danceSchool);
            $danceSchool->addAllowedUser($this);
        }

        return $this;
    }

    public function removeDanceSchool(DanceSchool $danceSchool): static
    {
        if ($this->danceSchools->removeElement($danceSchool)) {
            $danceSchool->removeAllowedUser($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, DanceSchool>
     */
    public function getAdminDanceSchools(): Collection
    {
        return $this->adminDanceSchools;
    }

    public function addAdminDanceSchool(DanceSchool $adminDanceSchool): static
    {
        if (!$this->adminDanceSchools->contains($adminDanceSchool)) {
            $this->adminDanceSchools->add($adminDanceSchool);
            $adminDanceSchool->addAllowedAdminUser($this);
        }

        return $this;
    }

    public function removeAdminDanceSchool(DanceSchool $adminDanceSchool): static
    {
        if ($this->adminDanceSchools->removeElement($adminDanceSchool)) {
            $adminDanceSchool->removeAllowedAdminUser($this);
        }

        return $this;
    }
}
