<?php

namespace Fulll\App\Query;

use Fulll\App\Gateway\Exception\BadRequestException;
use Fulll\App\Gateway\Query\FleetRepositoryInterface;
use Fulll\App\Gateway\Query\ParkingVehicleRepositoryInterface;
use Fulll\App\Gateway\Query\Request\GetFleetByIdRequestInterface;
use Fulll\App\Gateway\Query\Response\GetFleetByIdResponseInterface;
use Fulll\Domain\Entity\Fleet;
use Small\CleanApplication\Contract\RequestInterface;
use Small\CleanApplication\Contract\ResponseInterface;
use Small\CleanApplication\Contract\UseCaseInterface;

class GetFleetByIdQuery implements UseCaseInterface
{

    public function __construct(
        private FleetRepositoryInterface $fleetRepository,
        private ParkingVehicleRepositoryInterface $parkingVehicleRepository,
    ) {}

    public function execute(RequestInterface $request): ResponseInterface
    {

        if (!$request instanceof GetFleetByIdRequestInterface) {
            throw new BadRequestException('Request must be implements ' . GetFleetByIdRequestInterface::class);
        }


        $fleet = $this->fleetRepository->getFleetById($request->getFleetId());
        $fleet->setVehicles(
            $this->fleetRepository->getVehiclesInFleet($fleet)
        );

        if ($fleet->getId() === null) {
            throw new \LogicException('fleet is is null !');
        }

        $fleet->setLastParkingVehicles(
            $this->parkingVehicleRepository->getParkingVehicleByFleetId($fleet->getId())
        );

        return new readonly class($fleet) implements GetFleetByIdResponseInterface
        {

            public function __construct(
                private Fleet $fleet,
            ) {}

            public function getFleet(): Fleet
            {
                return $this->fleet;
            }

        };

    }


}