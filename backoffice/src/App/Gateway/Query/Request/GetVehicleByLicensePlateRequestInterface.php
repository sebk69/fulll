<?php

namespace Fulll\App\Gateway\Query\Request;

use Small\CleanApplication\Contract\RequestInterface;

interface GetVehicleByLicensePlateRequestInterface extends RequestInterface
{

    public function getLicensePlate(): string;

}