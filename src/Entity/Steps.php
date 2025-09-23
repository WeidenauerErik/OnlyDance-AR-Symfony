<?php

namespace App\Entity;

use App\Repository\StepsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StepsRepository::class)]
class Steps
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'steps')]
    private ?Dance $dance_id = null;

    #[ORM\Column]
    private ?float $m1_x = null;

    #[ORM\Column]
    private ?float $m1_y = null;

    #[ORM\Column]
    private ?bool $m1_toe = null;

    #[ORM\Column]
    private ?bool $m1_heel = null;

    #[ORM\Column]
    private ?int $m1_rotate = null;

    #[ORM\Column]
    private ?float $m2_x = null;

    #[ORM\Column]
    private ?float $m2_y = null;

    #[ORM\Column]
    private ?bool $m2_toe = null;

    #[ORM\Column]
    private ?bool $m2_heel = null;

    #[ORM\Column]
    private ?int $m2_rotate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDanceId(): ?Dance
    {
        return $this->dance_id;
    }

    public function setDanceId(?Dance $dance_id): static
    {
        $this->dance_id = $dance_id;

        return $this;
    }

    public function getM1X(): ?float
    {
        return $this->m1_x;
    }

    public function setM1X(float $m1_x): static
    {
        $this->m1_x = $m1_x;

        return $this;
    }

    public function getM1Y(): ?float
    {
        return $this->m1_y;
    }

    public function setM1Y(float $m1_y): static
    {
        $this->m1_y = $m1_y;

        return $this;
    }

    public function isM1Toe(): ?bool
    {
        return $this->m1_toe;
    }

    public function setM1Toe(bool $m1_toe): static
    {
        $this->m1_toe = $m1_toe;

        return $this;
    }

    public function isM1Heel(): ?bool
    {
        return $this->m1_heel;
    }

    public function setM1Heel(bool $m1_heel): static
    {
        $this->m1_heel = $m1_heel;

        return $this;
    }

    public function getM1Rotate(): ?int
    {
        return $this->m1_rotate;
    }

    public function setM1Rotate(int $m1_rotate): static
    {
        $this->m1_rotate = $m1_rotate;

        return $this;
    }

    public function getM2X(): ?float
    {
        return $this->m2_x;
    }

    public function setM2X(float $m2_x): static
    {
        $this->m2_x = $m2_x;

        return $this;
    }

    public function getM2Y(): ?float
    {
        return $this->m2_y;
    }

    public function setM2Y(float $m2_y): static
    {
        $this->m2_y = $m2_y;

        return $this;
    }

    public function isM2Toe(): ?bool
    {
        return $this->m2_toe;
    }

    public function setM2Toe(bool $m2_toe): static
    {
        $this->m2_toe = $m2_toe;

        return $this;
    }

    public function isM2Heel(): ?bool
    {
        return $this->m2_heel;
    }

    public function setM2Heel(bool $m2_heel): static
    {
        $this->m2_heel = $m2_heel;

        return $this;
    }

    public function getM2Rotate(): ?int
    {
        return $this->m2_rotate;
    }

    public function setM2Rotate(int $m2_rotate): static
    {
        $this->m2_rotate = $m2_rotate;

        return $this;
    }
}
