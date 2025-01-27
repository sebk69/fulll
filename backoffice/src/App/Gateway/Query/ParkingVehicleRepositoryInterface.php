<?php

namespace Fulll\App\Gateway\Query;

use Fulll\Domain\Collection\FleetVehicleCollection;
use Fulll\Domain\Collection\ParkingVehicleCollection;
use Fulll\Domain\Entity\Fleet;
use Fulll\Domain\Entity\ParkingVehicle;

interface ParkingVehicleRepositoryInterface
{

    public function getParkingVehicleByFleetId(string $idFleet): ParkingVehicleCollection;

}