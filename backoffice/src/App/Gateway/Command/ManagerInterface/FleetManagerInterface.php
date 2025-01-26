<?php

namespace Fulll\App\Gateway\Command\ManagerInterface;

use Fulll\Domain\Entity\Fleet;

interface FleetManagerInterface
{

    public function saveFleet(Fleet $fleet);
    public function fleetHasVehicle(string $fleetId, string $vehicleId): bool;
    public function addVehicleToFleet(string $fleetId, string $vehicleId): bool;

}