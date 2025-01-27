<?php

namespace Fulll\App\Gateway\Command\ManagerInterface;

use Fulll\Domain\Entity\Vehicle;

interface VehicleManagerInterface
{

    public function saveVehicle(Vehicle $vehicle): self;

}