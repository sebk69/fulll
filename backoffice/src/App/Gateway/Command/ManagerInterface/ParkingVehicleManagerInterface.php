<?php

namespace Fulll\App\Gateway\Command\ManagerInterface;

use Fulll\Domain\Entity\ParkingVehicle;

interface ParkingVehicleManagerInterface
{

    public function saveParkingVehicle(ParkingVehicle $parkingVehicle): self;
    public function getParkingVehicleById(string $id): ParkingVehicle;

}