<?php

namespace Fulll\Domain\Entity;

use Fulll\Domain\Collection\FleetCollection;
use Fulll\Domain\Entity\Trait\HasIdentifier;
use Small\SwooleEntityManager\Entity\AbstractEntity;
use Small\SwooleEntityManager\Entity\Attribute\OrmEntity;

class Vehicle
{

    use HasIdentifier;

    private ?string $licensePlate = null;
    private FleetCollection $fleets;
    private ?Location $location = null;

    public function __construct()
    {

        $this->fleets = new FleetCollection();

    }

    public static function create(string $licensePlate): self
    {

        return (new self())
            ->generateId()
            ->setLicensePlate($licensePlate)
        ;

    }

    public function getLicensePlate(): ?string
    {
        return $this->licensePlate;
    }

    public function setLicensePlate(?string $licensePlate): self
    {
        $this->licensePlate = $licensePlate;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getFleets(): FleetCollection
    {
        return $this->fleets;
    }

    public function setFleets(FleetCollection $fleets): self
    {
        $this->fleets = $fleets;

        return $this;
    }

}