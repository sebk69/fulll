<?php

namespace Fulll\App\Gateway\Query;

use Fulll\Domain\Entity\Fleet;

interface FleetManagerInterface
{

    public function getFleetById(int $id): Fleet;

}