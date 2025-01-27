<?php

namespace Fulll\Infra\Orm\ReadModel\Collection;

use Fulll\Infra\Orm\ReadModel\Entity\VehicleInFleet;
use Small\Collection\Collection\Attribute\CheckItemClass;
use Small\SwooleEntityManager\Entity\EntityCollection;

#[CheckItemClass(VehicleInFleet::class)]
class VehicleInFleetCollection extends EntityCollection
{

}