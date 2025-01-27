<?php

namespace Fulll\App\Command;

use Fulll\App\Gateway\Command\ManagerInterface\VehicleManagerInterface;
use Fulll\App\Gateway\Command\Request\AddUserRequestInterface;
use Fulll\App\Gateway\Command\Request\AddVehicleRequestInterface;
use Fulll\App\Gateway\Command\Request\LocalizeVehicleRequestInterface;
use Fulll\App\Gateway\Command\Response\AddVehicleResponseInterface;
use Fulll\App\Gateway\Exception\BadRequestException;
use Fulll\Domain\Entity\Vehicle;
use Small\CleanApplication\Contract\RequestInterface;
use Small\CleanApplication\Contract\ResponseInterface;
use Small\CleanApplication\Contract\UseCaseInterface;

final class LocalizeVehicleCommand implements UseCaseInterface
{

    public function __construct(
        private VehicleManagerInterface $vehicleManager,
    ) {}

    public function execute(RequestInterface $request): ResponseInterface
    {

        if (!$request instanceof LocalizeVehicleRequestInterface) {
            throw new BadRequestException(
                'Request must be implements ' . LocalizeVehicleRequestInterface::class
            );
        }

        $this->vehicleManager
            ->saveVehicle(
                $request->getVehicle()
                    ->setLocation($request->getLocation())
            );

        return new readonly class() implements ResponseInterface {};

    }

}