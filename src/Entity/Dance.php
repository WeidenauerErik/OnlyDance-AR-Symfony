<?php

namespace App\Entity;

use App\Repository\DanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DanceRepository::class)]
class Dance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?int $BPM = null;

    /**
     * @var Collection<int, Steps>
     */
    #[ORM\OneToMany(targetEntity: Steps::class, mappedBy: 'dance_id')]
    private Collection $steps;

    #[ORM\ManyToOne(inversedBy: 'dances')]
    private ?DanceSchool $owner = null;

    public function __construct()
    {
        $this->steps = new ArrayCollection();
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

    public function getBPM(): ?int
    {
        return $this->BPM;
    }

    public function setBPM(?int $BPM): static
    {
        $this->BPM = $BPM;

        return $this;
    }

    /**
     * @return Collection<int, Steps>
     */
    public function getSteps(): Collection
    {
        return $this->steps;
    }

    public function addStep(Steps $step): static
    {
        if (!$this->steps->contains($step)) {
            $this->steps->add($step);
            $step->setDanceId($this);
        }

        return $this;
    }

    public function removeStep(Steps $step): static
    {
        if ($this->steps->removeElement($step)) {
            // set the owning side to null (unless already changed)
            if ($step->getDanceId() === $this) {
                $step->setDanceId(null);
            }
        }

        return $this;
    }

    public function getOwner(): ?DanceSchool
    {
        return $this->owner;
    }

    public function setOwner(?DanceSchool $owner): static
    {
        $this->owner = $owner;

        return $this;
    }
}
