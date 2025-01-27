<?php

namespace Fulll\App\Gateway\Query\Request;

use Small\CleanApplication\Contract\RequestInterface;

interface GetFleetByIdRequestInterface extends RequestInterface
{

    public function getFleetId(): string;

}