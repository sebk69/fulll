<?php

namespace App\Command;

use Fulll\App\Command\AddUser;
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
        $this->addArgument('username', InputArgument::REQUIRED, 'Username');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        /** @var AddUserResponseInterface $response */
        $response = Facade::execute(

            AddUser::class,

            new readonly class($input->getArgument('username')) implements AddUserRequestInterface
            {

                public function __construct(
                    private string $username
                ) {}

                public function getUsername(): string
                {
                    return $this->username;
                }


            }

        );

        $output->writeln('User id : <fg=green>' . $response->getUser()->getId() . '</>');

        return self::SUCCESS;

    }


}