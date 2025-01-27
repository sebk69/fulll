<?php

namespace Fulll\Infra\Orm\WriteModel\Manager;

use Fulll\App\Gateway\Command\ManagerInterface\FleetManagerInterface;
use Fulll\App\Gateway\Command\ManagerInterface\VehicleManagerInterface;
use Fulll\Infra\Orm\WriteModel\Entity\Fleet;
use Fulll\Infra\Orm\WriteModel\Entity\Vehicle;
use Fulll\Infra\Orm\WriteModel\Entity\VehicleInFleet;
use Fulll\Infra\Orm\WriteModel\Manager\Exception\PersistFailException;
use Small\Collection\Collection\StringCollection;
use Small\Forms\Adapter\AnnotationAdapter;
use Small\Forms\Form\FormBuilder;
use Small\Forms\ValidationRule\Exception\ValidationFailException;
use Small\SwooleEntityManager\EntityManager\AbstractRelationnalManager;
use Small\SwooleEntityManager\EntityManager\Attribute\Connection;
use Small\SwooleEntityManager\EntityManager\Attribute\Entity;
use Small\SwooleEntityManager\EntityManager\Exception\EmptyResultException;

#[Entity(Vehicle::class)]
#[Connection('vehicle', 'writer')]
class VehicleManager extends AbstractRelationnalManager
    implements VehicleManagerInterface
{

    public function saveVehicle(\Fulll\Domain\Entity\Vehicle $vehicle): self
    {

        $formArray = [
            'id' => $vehicle->getId(),
            'licensePlate' => $vehicle->getLicensePlate(),
            'localization' => $vehicle->getLocation() === null ? null : [
                'longitude' => $vehicle->getLocation()->getLongitude(),
                'latitude' => $vehicle->getLocation()->getLatitude(),
                'altitude' => $vehicle->getLocation()->getAltitude(),
            ],
        ];

        $messages = new StringCollection();
        try {
            /** @var Vehicle $ormVehicle */
            $ormVehicle = $this->newEntity();
            FormBuilder::createFromAdapter(new AnnotationAdapter($ormVehicle))
                ->fillFromArray($formArray)
                ->validate($messages, true)
                ->hydrate($ormVehicle);
        } catch (ValidationFailException) {
            throw (new PersistFailException('Can\t persist vehicle'))
                ->setReasons($messages);
        }

        try {
            $this->findOneBy(['id' => $vehicle->getId()]);
            $ormVehicle->fromDb = true;
            $ormVehicle->setOriginalPrimaryKeys();
        } catch (EmptyResultException) {}

        $ormVehicle->persist();

        return $this;

    }

}