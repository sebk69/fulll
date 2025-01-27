<?php

namespace Fulll\App\Gateway\Query;

use Fulll\Domain\Entity\Vehicle;

interface VehicleRepositoryInterface
{

    public function getVehicleByLicensePlate(string $licensePlate): Vehicle;

}