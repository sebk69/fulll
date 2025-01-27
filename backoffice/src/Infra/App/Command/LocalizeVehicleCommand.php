<?php

namespace App\Command;

use Fulll\App\Command\AddVehicleCommand;
use Fulll\App\Gateway\Command\Request\AddVehicleRequestInterface;
use Fulll\App\Gateway\Command\Request\LocalizeVehicleRequestInterface;
use Fulll\App\Gateway\Command\Request\ParkVehicleRequestInterface;
use Fulll\App\Gateway\Command\Request\RegisterVehicleRequestInterface;
use Fulll\App\Gateway\Exception\VehicleNotFoundException;
use Fulll\App\Gateway\Query\Request\GetFleetByIdRequestInterface;
use Fulll\App\Gateway\Query\Request\GetVehicleByLicensePlateRequestInterface;
use Fulll\App\Query\GetFleetByIdQuery;
use Fulll\App\Query\GetVehicleByLicensePlateQuery;
use Fulll\Domain\Entity\Fleet;
use Fulll\Domain\Entity\Location;
use Fulll\Domain\Entity\Vehicle;
use Small\CleanApplication\Facade;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('app:vehicle:localize')]
class LocalizeVehicleCommand extends Command
{

    protected function configure(): void
    {
        $this->addArgument('fleetId', InputArgument::REQUIRED, 'Fleet id');
        $this->addArgument('licensePlate', InputArgument::REQUIRED, 'Vehicle license plate');
        $this->addArgument('latitude', InputArgument::REQUIRED, 'Latitude of vehicle');
        $this->addArgument('longitude', InputArgument::REQUIRED, 'Longitude of vehicle');
        $this->addArgument('altitude', InputArgument::OPTIONAL, 'Altitude of vehicle');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        /** @var Fleet $fleet */
        $fleet = Facade::execute(

            GetFleetByIdQuery::class,

            new readonly class($input->getArgument('fleetId')) implements GetFleetByIdRequestInterface
            {

                public function __construct(
                    private string $fleetId,
                ) {}

                public function getFleetId(): string
                {
                    return $this->fleetId;
                }

            }

        )->getFleet();

        /** @var Vehicle $vehicle */
        $vehicle = Facade::execute(

            GetVehicleByLicensePlateQuery::class,

            new readonly class($input->getArgument('licensePlate'))
                implements GetVehicleByLicensePlateRequestInterface {

                public function __construct(
                    private string $licensePlate,
                )
                {
                }

                public function getLicensePlate(): string
                {

                    return $this->licensePlate;

                }

            }

        )->getVehicle();

        Facade::execute(

            \Fulll\App\Command\LocalizeVehicleCommand::class,

            new readonly class($vehicle,
                (new Location())
                    ->setLatitude($input->getArgument('latitude'))
                    ->setLongitude($input->getArgument('longitude'))
                    ->setAltitude(
                        $input->hasArgument('altitude')
                            ? $input->getArgument('altitude')
                            : null
                    )
            ) implements LocalizeVehicleRequestInterface
            {

                public function __construct(
                    private Vehicle $vehicle,
                    private Location $location,
                ) {}

                public function getVehicle(): Vehicle
                {
                    return $this->vehicle;
                }

                public function getLocation(): Location
                {
                    return $this->location;
                }

            }

        );

        Facade::execute(

            \Fulll\App\Command\ParkVehicleCommand::class,

            new readonly class($fleet, $vehicle) implements ParkVehicleRequestInterface
            {

                public function __construct(
                    private Fleet $fleet,
                    private Vehicle $vehicle,
                ) {}

                public function getFleet(): Fleet
                {
                    return $this->fleet;
                }

                public function getVehicle(): Vehicle
                {
                    return $this->vehicle;
                }

            }

        );

        return self::SUCCESS;

    }


}