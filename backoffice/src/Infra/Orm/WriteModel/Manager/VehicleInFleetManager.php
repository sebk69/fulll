<?php

namespace Fulll\Infra\Orm\WriteModel\Manager;

use Fulll\Infra\Orm\WriteModel\Entity\VehicleInFleet;
use Small\SwooleEntityManager\EntityManager\AbstractRelationnalManager;
use Small\SwooleEntityManager\EntityManager\Attribute\Connection;
use Small\SwooleEntityManager\EntityManager\Attribute\Entity;

#[Entity(VehicleInFleet::class)]
#[Connection('vehicle_in_fleet', 'writer')]
class VehicleInFleetManager extends AbstractRelationnalManager
{
}