<?php

namespace Fulll\Domain\Collection;

use Fulll\Domain\Collection\Attribute\CheckItemClass;
use Fulll\Domain\Collection\Exception\VehicleNotFoundException;
use Fulll\Domain\Entity\Vehicle;

/**
 * @method Vehicle current()
 * @method Vehicle offsetGet(mixed $offset)
 */
#[CheckItemClass(Vehicle::class)]
class VehicleCollection extends Collection
{

    public function getVehicleInCollection(string $vehicleId): Vehicle
    {

        foreach ($this as $vehicle) {
            if ($vehicle->getId() === $vehicleId) {
                return $vehicle;
            }
        }

        throw new VehicleNotFoundException('Vehicle #' . $vehicleId . ' not in collection');

    }

    public function getVehicleByLicensePlate(string $licencePlate): Vehicle
    {

        foreach ($this as $vehicle) {
            if ($vehicle->getLicensePlate() === $licencePlate) {
                return $vehicle;
            }
        }

        throw new VehicleNotFoundException('Vehicle ' . $licencePlate . ' not in collection');

    }

}