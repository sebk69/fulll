<?php

namespace Fulll\Domain\Collection;

use Fulll\Domain\Collection\Attribute\CheckItemClass;
use Fulll\Domain\Collection\Exception\VehicleNotFoundException;
use Fulll\Domain\Entity\Fleet;
use Fulll\Domain\Entity\Vehicle;

#[CheckItemClass(Vehicle::class)]
class FleetVehicleCollection extends VehicleCollection
{

    public function setLastParkedPosition(Fleet $fleet, Vehicle $vehicle): self
    {

        try {
            $this->getVehicleInCollection(
                $vehicle->getId()
            );
        } catch (VehicleNotFoundException) {
            throw new VehicleNotFoundException('Vehicle not found in fleet');
        }

        return $this;

    }

}