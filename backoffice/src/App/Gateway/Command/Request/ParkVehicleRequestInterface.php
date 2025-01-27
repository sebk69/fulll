<?php

namespace Fulll\App\Gateway\Command\Request;

use Fulll\Domain\Entity\Fleet;
use Fulll\Domain\Entity\User;
use Fulll\Domain\Entity\Vehicle;
use Small\CleanApplication\Contract\RequestInterface;

interface ParkVehicleRequestInterface extends RequestInterface
{

    public function getFleet(): Fleet|User;
    public function getVehicle(): Vehicle;

}