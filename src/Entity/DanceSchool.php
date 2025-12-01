<?php

namespace App\Entity;

use App\Repository\DanceSchoolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DanceSchoolRepository::class)]
class DanceSchool
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'danceSchools')]
    #[ORM\JoinTable(name: 'dance_school_allowed_user')]
    private Collection $allowedUser;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'adminDanceSchools')]
    #[ORM\JoinTable(name: 'dance_school_allowed_admin_user')]
    private Collection $allowedAdminUser;


    /**
     * @var Collection<int, Dance>
     */
    #[ORM\OneToMany(targetEntity: Dance::class, mappedBy: 'owner')]
    private Collection $dances;

    public function __construct()
    {
        $this->allowedUser = new ArrayCollection();
        $this->allowedAdminUser = new ArrayCollection();
        $this->dances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getAllowedUser(): Collection
    {
        return $this->allowedUser;
    }

    public function addAllowedUser(User $allowedUser): static
    {
        if (!$this->allowedUser->contains($allowedUser)) {
            $this->allowedUser->add($allowedUser);
        }

        return $this;
    }

    public function removeAllowedUser(User $allowedUser): static
    {
        $this->allowedUser->removeElement($allowedUser);

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getAllowedAdminUser(): Collection
    {
        return $this->allowedAdminUser;
    }

    public function addAllowedAdminUser(User $allowedAdminUser): static
    {
        if (!$this->allowedAdminUser->contains($allowedAdminUser)) {
            $this->allowedAdminUser->add($allowedAdminUser);
        }

        return $this;
    }

    public function removeAllowedAdminUser(User $allowedAdminUser): static
    {
        $this->allowedAdminUser->removeElement($allowedAdminUser);

        return $this;
    }

    /**
     * @return Collection<int, Dance>
     */
    public function getDances(): Collection
    {
        return $this->dances;
    }

    public function addDance(Dance $dance): static
    {
        if (!$this->dances->contains($dance)) {
            $this->dances->add($dance);
            $dance->setOwner($this);
        }

        return $this;
    }

    public function removeDance(Dance $dance): static
    {
        if ($this->dances->removeElement($dance)) {
            // set the owning side to null (unless already changed)
            if ($dance->getOwner() === $this) {
                $dance->setOwner(null);
            }
        }

        return $this;
    }
}
