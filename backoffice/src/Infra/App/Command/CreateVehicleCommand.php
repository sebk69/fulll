<?php

namespace App\Command;

use Fulll\App\Command\AddUserCommand;
use Fulll\App\Command\AddVehicleCommand;
use Fulll\App\Command\RegisterVehicleCommand;
use Fulll\App\Gateway\Command\Request\AddUserRequestInterface;
use Fulll\App\Gateway\Command\Request\AddVehicleRequestInterface;
use Fulll\App\Gateway\Command\Response\AddUserResponseInterface;
use Fulll\App\Gateway\Command\Response\AddVehicleResponseInterface;
use Small\CleanApplication\Facade;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('app:vehicle:create')]
class CreateVehicleCommand extends Command
{

    protected function configure(): void
    {
        $this->addArgument('fleetId', InputArgument::REQUIRED, 'Fleet id');
        $this->addArgument('licensePlate', InputArgument::REQUIRED, 'License plate');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        /** @var AddVehicleResponseInterface $response */
        $response = Facade::execute(

            RegisterVehicleCommand::class,

            new readonly class($input->getArgument('licensePlate')) implements AddVehicleRequestInterface
            {

                public function __construct(
                    private string $licensePlate
                ) {}

                public function getLicensePlate(): string
                {
                    return $this->licensePlate;
                }


            }

        );

        $output->writeln('Vehicle id : <fg=green>' . $response->getVehicle()->getId() . '</>');

        return self::SUCCESS;

    }


}