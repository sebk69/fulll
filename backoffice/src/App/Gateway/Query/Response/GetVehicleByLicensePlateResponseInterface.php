<?php

namespace Fulll\App\Gateway\Query\Response;

use Fulll\Domain\Entity\Vehicle;
use Small\CleanApplication\Contract\ResponseInterface;

interface GetVehicleByLicensePlateResponseInterface extends ResponseInterface
{

    public function getVehicle(): Vehicle;

}