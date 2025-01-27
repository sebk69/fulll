<?php

namespace Fulll\App\Gateway\Command\ManagerInterface;

use Fulll\Domain\Entity\User;

interface UserManagerInterface
{

    public function saveUser(User $user): self;

}