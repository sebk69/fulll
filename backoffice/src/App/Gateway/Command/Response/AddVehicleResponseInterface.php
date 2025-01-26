<?php

namespace Fulll\App\Gateway\Command\Response;

use Fulll\Domain\Entity\User;
use Fulll\Domain\Entity\Vehicle;
use Small\CleanApplication\Contract\ResponseInterface;

interface AddVehicleResponseInterface extends ResponseInterface
{

    public function getVehicle(): Vehicle;

}