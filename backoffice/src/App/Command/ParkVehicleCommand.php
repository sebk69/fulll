<?php

namespace Fulll\App\Command;

use Fulll\App\Command\Exception\AlreadyParkedHereException;
use Fulll\App\Gateway\Command\ManagerInterface\ParkingVehicleManagerInterface;
use Fulll\App\Gateway\Command\Request\ParkVehicleRequestInterface;
use Fulll\App\Gateway\Command\Request\RegisterVehicleRequestInterface;
use Fulll\App\Gateway\Exception\BadRequestException;
use Fulll\Domain\Collection\Exception\VehicleNotFoundException;
use Fulll\Domain\Entity\Fleet;
use Fulll\Domain\Entity\User;
use Fulll\Domain\Entity\Vehicle;
use Small\CleanApplication\Contract\RequestInterface;
use Small\CleanApplication\Contract\ResponseInterface;
use Small\CleanApplication\Contract\UseCaseInterface;

final class ParkVehicleCommand implements UseCaseInterface
{

    public function __construct(
        private ParkingVehicleManagerInterface $parkingVehicleManager,
    ) {}

    public function execute(RequestInterface $request): ResponseInterface
    {

        if (!$request instanceof ParkVehicleRequestInterface) {
            throw new BadRequestException('Request must implement ' . ParkVehicleRequestInterface::class);
        }

        if ($request->getFleet() instanceof User) {
            $myFleet = $request->getFleet()->getMyFleet() ?? throw new \LogicException('my fleet undefined !');
        } else {
            $myFleet = $request->getFleet();
        }

        // Check vehicle in fleet
        $found = true;
        try {
            $myFleet
                ->getVehicles()
                ->getVehicleByLicensePlate($request->getVehicle()->getLicensePlate() ?? '');
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
            ->getParkgingForVehicle($myFleet, $request->getVehicle());

        if ($parking->isSameLocation($request->getVehicle()->getLocation())) {
            throw new AlreadyParkedHereException('Vehicle already parked at this position');
        }

        $parking->setLocation(
            $request->getVehicle()->getLocation() === null
                ? null
                : clone $request->getVehicle()->getLocation()
        );

        // Persist parking
        $this->parkingVehicleManager->saveParkingVehicle($parking);

        return new readonly class implements ResponseInterface {};

    }

}