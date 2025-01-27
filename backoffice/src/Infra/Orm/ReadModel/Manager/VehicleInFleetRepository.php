<?php

namespace Fulll\Infra\Orm\ReadModel\Manager;

use Fulll\App\Gateway\Command\ManagerInterface\FleetManagerInterface;
use Fulll\App\Gateway\Command\ManagerInterface\VehicleManagerInterface;
use Fulll\Infra\Orm\ReadModel\Entity\Fleet;
use Fulll\Infra\Orm\ReadModel\Entity\Vehicle;
use Fulll\Infra\Orm\ReadModel\Entity\VehicleInFleet;
use Fulll\Infra\Orm\ReadModel\Manager\Exception\PersistFailException;
use Small\Collection\Collection\StringCollection;
use Small\Forms\Adapter\AnnotationAdapter;
use Small\Forms\Form\FormBuilder;
use Small\Forms\ValidationRule\Exception\ValidationFailException;
use Small\SwooleEntityManager\EntityManager\AbstractRelationnalManager;
use Small\SwooleEntityManager\EntityManager\Attribute\Connection;
use Small\SwooleEntityManager\EntityManager\Attribute\Entity;

#[Entity(VehicleInFleet::class)]
#[Connection('vehicle_in_fleet', 'reader')]
class VehicleInFleetRepository extends AbstractRelationnalManager
{
}