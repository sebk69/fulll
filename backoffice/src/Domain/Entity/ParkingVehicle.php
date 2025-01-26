<?php

namespace Fulll\Domain\Entity;

use Fulll\Domain\Entity\Trait\HasIdentifier;

class ParkingVehicle
{

    use HasIdentifier;

    protected ?string $idVehicle = null;
    protected ?string $idFleet = null;
    protected ?Location $location = null;

    public static function create($idVehicle, $idFleet, ?Location $location): self
    {

        return (new self())
            ->generateId()
            ->setIdVehicle($idVehicle)
            ->setIdFleet($idFleet)
            ->setLocation($location);

    }

    public function getIdVehicle(): ?string
    {
        return $this->idVehicle;
    }

    public function setIdVehicle(?string $idVehicle): self
    {
        $this->idVehicle = $idVehicle;

        return $this;
    }

    public function getIdFleet(): ?string
    {
        return $this->idFleet;
    }

    public function setIdFleet(?string $idFleet): self
    {
        $this->idFleet = $idFleet;

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

    public function isSameLocation(Location $location): bool
    {

        return $this->location->getLatitude() === $location->getLatitude() &&
            $this->location->getLongitude() === $location->getLongitude() &&
            $this->location->getAltitude() === $location->getAltitude();

    }

}