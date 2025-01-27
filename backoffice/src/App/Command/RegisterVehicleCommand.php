<?php

namespace Fulll\App\Command;

use Fulll\App\Gateway\Command\ManagerInterface\FleetManagerInterface;
use Fulll\App\Gateway\Command\Request\RegisterVehicleRequestInterface;
use Fulll\App\Gateway\Exception\BadRequestException;
use Fulll\Domain\Entity\Exception\VehicleAlreadyRegistedException;
use Fulll\Domain\Entity\Fleet;
use Fulll\Domain\Entity\User;
use Fulll\Domain\Entity\Vehicle;
use Small\CleanApplication\Contract\RequestInterface;
use Small\CleanApplication\Contract\ResponseInterface;
use Small\CleanApplication\Contract\UseCaseInterface;

final class RegisterVehicleCommand implements UseCaseInterface
{

    public function __construct(
        private FleetManagerInterface $fleetManager,
    ) {}

    public function execute(RequestInterface $request): ResponseInterface
    {

        if (!$request instanceof RegisterVehicleRequestInterface) {
            throw new BadRequestException('Request must implement ' . RegisterVehicleRequestInterface::class);
        }

        if ($request->getFleet() instanceof User) {
            $myFleet = $request->getFleet()->getMyFleet();
        } else {
            $myFleet = $request->getFleet();
        }

        // Check vehicle don't exist in my fleet
        if (
            $this
                ->fleetManager
                ->fleetHasVehicle($myFleet->getId(), $request->getVehicle()->getId())
        ) {
            throw new VehicleAlreadyRegistedException(
                'Vehicle already exists in fleet #' . $myFleet->getId()
            );
        }

        // Persist vehicle into fleet
        $this->fleetManager
            ->addVehicleToFleet($myFleet->getId(), $request->getVehicle()->getId());

        // Add vehicle to the fleet
        $myFleet
            ->getVehicles()
            ->push($request->getVehicle());

        // Add fleet to vehicle
        $request->getVehicle()
            ->getFleets()
            ->push($myFleet);

        return new readonly class implements ResponseInterface {};

    }

}