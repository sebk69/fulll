<?php

namespace Fulll\Domain\Entity;

use Fulll\Domain\Collection\FleetVehicleCollection;
use Fulll\Domain\Collection\ParkingVehicleCollection;
use Fulll\Domain\Entity\Trait\HasIdentifier;

class Fleet
{

    use HasIdentifier;

    private FleetVehicleCollection $vehicles;
    private ParkingVehicleCollection $lastParkingVehicles;

    public function __construct()
    {
        $this->vehicles = new FleetVehicleCollection();
        $this->lastParkingVehicles = new ParkingVehicleCollection();
    }

    public static function create(): self
    {

        return (new self())
            ->generateId();

    }

    public function getVehicles(): FleetVehicleCollection
    {
        return $this->vehicles;
    }

    public function setVehicles(FleetVehicleCollection $vehicles): self
    {
        $this->vehicles = $vehicles;

        return $this;
    }

    public function getLastParkingVehicles(): ParkingVehicleCollection
    {
        return $this->lastParkingVehicles;
    }

    public function setLastParkingVehicles(ParkingVehicleCollection $lastParkingVehicles): self
    {
        $this->lastParkingVehicles = $lastParkingVehicles;

        return $this;
    }

}