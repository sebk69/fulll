<?php

namespace Fulll\App\Gateway\Command\Request;

use Small\CleanApplication\Contract\RequestInterface;

interface AddUserRequestInterface extends RequestInterface
{

    public function getUserId(): string;

}