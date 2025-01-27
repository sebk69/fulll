<?php

namespace App\Command;

use Fulll\App\Command\AddUserCommand;
use Fulll\App\Gateway\Command\Request\AddUserRequestInterface;
use Fulll\App\Gateway\Command\Response\AddUserResponseInterface;
use Small\CleanApplication\Facade;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('app:user:create')]
class CreateUserCommand extends Command
{

    protected function configure(): void
    {
        $this->addArgument('userId', InputArgument::REQUIRED, 'Id of the user.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        /** @var AddUserResponseInterface $response */
        $response = Facade::execute(

            AddUserCommand::class,

            new readonly class($input->getArgument('userId')) implements AddUserRequestInterface
            {

                public function __construct(
                    private string $userId
                ) {}

                public function getUserId(): string
                {
                    return $this->userId;
                }


            }

        );

        $output->writeln('Fleet id : <fg=green>' . $response->getUser()->getMyFleet()->getId() . '</>');

        return self::SUCCESS;

    }


}