<?php

namespace App\Entity;

use App\Repository\MeasurementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MeasurementRepository::class)]
class Measurement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 0)]
    private ?string $windiness = null;

    #[ORM\Column]
    private ?int $temprature = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date = null;

    #[ORM\Column]
    private ?int $cloudiness = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $rainfall = null;

    #[ORM\ManyToOne(inversedBy: 'measurements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Location $location = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWindiness(): ?string
    {
        return $this->windiness;
    }

    public function setWindiness(string $windiness): static
    {
        $this->windiness = $windiness;

        return $this;
    }

    public function getTemprature(): ?int
    {
        return $this->temprature;
    }

    public function setTemprature(int $temprature): static
    {
        $this->temprature = $temprature;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getCloudiness(): ?int
    {
        return $this->cloudiness;
    }

    public function setCloudiness(int $cloudiness): static
    {
        $this->cloudiness = $cloudiness;

        return $this;
    }

    public function getRainfall(): ?string
    {
        return $this->rainfall;
    }

    public function setRainfall(string $rainfall): static
    {
        $this->rainfall = $rainfall;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): static
    {
        $this->location = $location;

        return $this;
    }
}
