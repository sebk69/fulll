<?php

namespace Fulll\App\Gateway\Query\Response;

use Fulll\Domain\Entity\Fleet;
use Small\CleanApplication\Contract\ResponseInterface;

interface GetFleetByIdResponseInterface extends ResponseInterface
{

    public function getFleet(): Fleet;

}