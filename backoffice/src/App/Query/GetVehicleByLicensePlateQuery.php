<?php

namespace Fulll\App\Query;

use Fulll\App\Gateway\Exception\BadRequestException;
use Fulll\App\Gateway\Query\FleetRepositoryInterface;
use Fulll\App\Gateway\Query\Request\GetFleetByIdRequestInterface;
use Fulll\App\Gateway\Query\Request\GetVehicleByLicensePlateRequestInterface;
use Fulll\App\Gateway\Query\Response\GetFleetByIdResponseInterface;
use Fulll\App\Gateway\Query\Response\GetVehicleByLicensePlateResponseInterface;
use Fulll\App\Gateway\Query\VehicleRepositoryInterface;
use Fulll\Domain\Entity\Fleet;
use Fulll\Domain\Entity\Vehicle;
use Fulll\Infra\Orm\ReadModel\Manager\VehicleRepository;
use Small\CleanApplication\Contract\RequestInterface;
use Small\CleanApplication\Contract\ResponseInterface;
use Small\CleanApplication\Contract\UseCaseInterface;

class GetVehicleByLicensePlateQuery implements UseCaseInterface
{

    public function __construct(
        private VehicleRepositoryInterface $vehicleRepository,
    ) {}

    public function execute(RequestInterface $request): ResponseInterface
    {

        if (!$request instanceof GetVehicleByLicensePlateRequestInterface) {
            throw new BadRequestException('Request must be implements ' . GetVehicleByLicensePlateRequestInterface::class);
        }

        $vehicle = $this->vehicleRepository->getVehicleByLicensePlate($request->getLicensePlate());

        return new readonly class($vehicle) implements GetVehicleByLicensePlateResponseInterface
        {

            public function __construct(
                private Vehicle $vehicle,
            ) {}

            public function getVehicle(): Vehicle
            {
                return $this->vehicle;
            }

        };

    }


}