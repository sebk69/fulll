<?php

namespace Fulll\App\Command;

use Fulll\Domain\Collection\Exception\VehicleNotFoundException;
use Fulll\Domain\Entity\Exception\VehicleAlreadyRegistedException;
use Fulll\Domain\Entity\Fleet;
use Fulll\Domain\Entity\User;
use Fulll\Domain\Entity\Vehicle;

class RegisterVehicle
{

    public function execute(
        Fleet|User $myFleet,
        Vehicle $vehicle,
    ): self {

        if ($myFleet instanceof User) {
            $myFleet = $myFleet->getMyFleet();
        }

        // Check vehicle don't exist in my fleet
        $found = true;
        try {
            $myFleet->getVehicles()->getVehicleByLicensePlate($vehicle->getLicensePlate());
        } catch (VehicleNotFoundException) {
            $found = false;
        }

        if ($found) {
            throw new VehicleAlreadyRegistedException(
                'Vehicle already exists in fleet #' . $myFleet->getId()
            );
        }

        // Add vehicle to the fleet
        $myFleet
            ->getVehicles()
            ->push($vehicle);

        // Add fleet to vehicle
        $vehicle->getFleets()->push($myFleet);

        return $this;

    }

}