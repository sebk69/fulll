<?php

namespace Fulll\App\Gateway\Command\ManagerInterface;

use Fulll\Domain\Entity\Fleet;

interface FleetManagerInterface
{

    public function saveFleet(Fleet $fleet): self;
    public function fleetHasVehicle(string $idFleet, string $idVehicle): bool;
    public function addVehicleToFleet(string $fleetId, string $vehicleId): self;

}