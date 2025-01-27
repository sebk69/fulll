<?php

namespace Fulll\App\Command;

use Fulll\App\Gateway\Command\ManagerInterface\FleetManagerInterface;
use Fulll\App\Gateway\Command\ManagerInterface\UserManagerInterface;
use Fulll\App\Gateway\Command\Request\AddUserRequestInterface;
use Fulll\App\Gateway\Command\Response\AddUserResponseInterface;
use Fulll\App\Gateway\Exception\BadRequestException;
use Fulll\Domain\Entity\User;
use Small\CleanApplication\Contract\RequestInterface;
use Small\CleanApplication\Contract\ResponseInterface;
use Small\CleanApplication\Contract\UseCaseInterface;

final class AddUserCommand implements UseCaseInterface
{

    public function __construct(
        private UserManagerInterface $userManager,
        private FleetManagerInterface $fleetManager,
    ) {}

    public function execute(RequestInterface $request): ResponseInterface
    {

        if (!$request instanceof AddUserRequestInterface) {
            throw new BadRequestException('Request must be implements ' . AddUserRequestInterface::class);
        }

        $user = User::create($request->getUserId());


        $this->fleetManager
            ->saveFleet($user->getMyFleet());

        $this
            ->userManager
            ->saveUser($user);

        return new readonly class($user) implements AddUserResponseInterface {

            public function __construct(
                private User $user
            ) {}

            public function getUser(): User
            {
                return $this->user;
            }

        };

    }

}