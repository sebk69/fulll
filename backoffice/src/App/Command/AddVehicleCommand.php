<?php

namespace Fulll\App\Command;

use Fulll\App\Gateway\Command\ManagerInterface\VehicleManagerInterface;
use Fulll\App\Gateway\Command\Request\AddUserRequestInterface;
use Fulll\App\Gateway\Command\Request\AddVehicleRequestInterface;
use Fulll\App\Gateway\Command\Response\AddVehicleResponseInterface;
use Fulll\App\Gateway\Exception\BadRequestException;
use Fulll\Domain\Entity\Vehicle;
use Small\CleanApplication\Contract\RequestInterface;
use Small\CleanApplication\Contract\ResponseInterface;
use Small\CleanApplication\Contract\UseCaseInterface;

final class AddVehicleCommand implements UseCaseInterface
{

    public function __construct(
        private VehicleManagerInterface $vehicleManager,
    ) {}

    public function execute(RequestInterface $request): ResponseInterface
    {

        if (!$request instanceof AddVehicleRequestInterface) {
            throw new BadRequestException('Request must be implements ' . AddVehicleRequestInterface::class);
        }

        $vehicle = Vehicle::create($request->getLicensePlate());


        $this->vehicleManager
            ->saveVehicle($vehicle);

        return new readonly class($vehicle) implements AddVehicleResponseInterface {

            public function __construct(
                private Vehicle $vehicle
            ) {}

            public function getVehicle(): Vehicle
            {
                return $this->vehicle;
            }

        };

    }

}