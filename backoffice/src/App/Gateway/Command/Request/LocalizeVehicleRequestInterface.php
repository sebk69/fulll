<?php

namespace Fulll\App\Gateway\Command\Request;

use Fulll\Domain\Entity\Fleet;
use Fulll\Domain\Entity\Location;
use Fulll\Domain\Entity\User;
use Fulll\Domain\Entity\Vehicle;
use Small\CleanApplication\Contract\RequestInterface;

interface LocalizeVehicleRequestInterface extends RequestInterface
{

    public function getVehicle(): Vehicle;
    public function getLocation(): Location;

}