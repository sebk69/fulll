<?php

namespace Fulll\Domain\Entity;

use Fulll\Domain\Collection\FleetVehicleCollection;
use Fulll\Domain\Collection\ParkingVehicleCollection;
use Fulll\Domain\Entity\Trait\HasIdentifier;

class Fleet
{

    use HasIdentifier;

    private ?string $idUser;
    private FleetVehicleCollection $vehicles;
    private ParkingVehicleCollection $lastParkingVehicles;

    public function __construct()
    {
        $this->vehicles = new FleetVehicleCollection();
        $this->lastParkingVehicles = new ParkingVehicleCollection();
    }

    public static function create(string $idUser): self
    {

        return (new self())
            ->generateId()
            ->setIdUser($idUser);

    }

    public function getIdUser(): ?string
    {
        return $this->idUser;
    }

    public function setIdUser(?string $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
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