<?php

namespace Fulll\App\Gateway\Command\Request;

use Small\CleanApplication\Contract\RequestInterface;

interface AddVehicleRequestInterface extends RequestInterface
{

    public function getLicensePlate(): string;

}