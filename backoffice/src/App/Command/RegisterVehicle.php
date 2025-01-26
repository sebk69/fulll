<?php

namespace Fulll\App\Command;

use Fulll\App\Gateway\Command\ManagerInterface\FleetManagerInterface;
use Fulll\Domain\Entity\Exception\VehicleAlreadyRegistedException;
use Fulll\Domain\Entity\Fleet;
use Fulll\Domain\Entity\User;
use Fulll\Domain\Entity\Vehicle;

final class RegisterVehicle
{

    public function __construct(
        private FleetManagerInterface $fleetManager,
    ) {}

    public function execute(
        Fleet|User $myFleet,
        Vehicle $vehicle,
    ): self {

        if ($myFleet instanceof User) {
            $myFleet = $myFleet->getMyFleet();
        }

        // Check vehicle don't exist in my fleet
        if (
            $this
                ->fleetManager
                ->fleetHasVehicle($myFleet->getId(), $vehicle->getId())
        ) {
            throw new VehicleAlreadyRegistedException(
                'Vehicle already exists in fleet #' . $myFleet->getId()
            );
        }

        // Persist vehicle into fleet
        $this->fleetManager
            ->addVehicleToFleet($myFleet->getId(), $vehicle->getId());

        // Add vehicle to the fleet
        $myFleet
            ->getVehicles()
            ->push($vehicle);

        // Add fleet to vehicle
        $vehicle
            ->getFleets()
            ->push($myFleet);

        return $this;

    }

}