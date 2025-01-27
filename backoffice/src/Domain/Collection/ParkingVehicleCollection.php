<?php

namespace Fulll\Domain\Collection;

use Fulll\Domain\Collection\Attribute\CheckItemClass;
use Fulll\Domain\Entity\Fleet;
use Fulll\Domain\Entity\Location;
use Fulll\Domain\Entity\ParkingVehicle;
use Fulll\Domain\Entity\Vehicle;

/**
 * @method ParkingVehicle current()
 * @method ParkingVehicle offsetGet(mixed $offset)
 */
#[CheckItemClass(ParkingVehicle::class)]
class ParkingVehicleCollection extends Collection
{

    public function getParkgingForVehicle(Fleet $fleet, Vehicle $vehicle): ParkingVehicle
    {

        foreach ($this as $parkingVehicle) {
            if ($parkingVehicle->getIdVehicle() === $vehicle->getId()) {
                return $parkingVehicle;
            }
        }

        $fleet->getLastParkingVehicles()->push(
            $parking = (new ParkingVehicle())
                ->generateId()
                ->setIdFleet($fleet->getId())
                ->setIdVehicle($vehicle->getId())
                ->setLocation(new Location())
        );

        return $parking;

    }

}