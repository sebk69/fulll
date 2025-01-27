<?php

namespace Fulll\App\Gateway\Query;

use Fulll\Domain\Collection\FleetVehicleCollection;
use Fulll\Domain\Entity\Fleet;

interface FleetRepositoryInterface
{

    public function getFleetById(string $fleetId): Fleet;
    public function getVehiclesInFleet(Fleet $fleet): FleetVehicleCollection;

}