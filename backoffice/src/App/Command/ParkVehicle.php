<?php

namespace Fulll\App\Command;

use Fulll\App\Command\Exception\AlreadyParkedHereException;
use Fulll\App\Gateway\Command\ManagerInterface\ParkingVehicleManagerInterface;
use Fulll\Domain\Collection\Exception\VehicleNotFoundException;
use Fulll\Domain\Entity\Fleet;
use Fulll\Domain\Entity\User;
use Fulll\Domain\Entity\Vehicle;

class ParkVehicle
{

    public function __construct(
        private ParkingVehicleManagerInterface $parkingVehicleManager,
    ) {}

    public function execute(
        Fleet|User $myFleet,
        Vehicle $vehicle,
    ) {

        if ($myFleet instanceof User) {
            $myFleet = $myFleet->getMyFleet();
        }

        // Check vehicle in fleet
        $found = true;
        try {
            $myFleet->getVehicles()->getVehicleByLicensePlate($vehicle->getLicensePlate());
        } catch (VehicleNotFoundException) {
            $found = false;
        }

        if (!$found) {
            throw new VehicleNotFoundException(
                'Vehicle don\'t exists in fleet #' . $myFleet->getId()
            );
        }

        // Park Vehicle
        $parking = $myFleet
            ->getLastParkingVehicles()
            ->getParkgingForVehicle($myFleet, $vehicle);

        if ($parking->isSameLocation($vehicle->getLocation())) {
            throw new AlreadyParkedHereException('Vehicle already parked at this position');
        }

        $parking->setLocation(clone $vehicle->getLocation());

        // Persist parking
        $this->parkingVehicleManager->saveParkingVehicle($parking);

    }

}