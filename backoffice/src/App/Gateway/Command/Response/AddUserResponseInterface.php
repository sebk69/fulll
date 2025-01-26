<?php

namespace Fulll\App\Gateway\Command\Response;

use Fulll\Domain\Entity\User;
use Small\CleanApplication\Contract\ResponseInterface;

interface AddUserResponseInterface extends ResponseInterface
{

    public function getUser(): User;

}