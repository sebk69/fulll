<?php

namespace Fulll\Infra\Orm\WriteModel\Manager;

use Fulll\App\Gateway\Command\ManagerInterface\FleetManagerInterface;
use Fulll\Infra\Orm\WriteModel\Entity\Fleet;
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

#[Entity(Fleet::class)]
#[Connection('fleet', 'writer')]
class FleetManager extends AbstractRelationnalManager
    implements FleetManagerInterface
{

    public function saveFleet(\Fulll\Domain\Entity\Fleet $fleet): self
    {

        try {
            FormBuilder::createFromAdapter(new AnnotationAdapter($ormFleet = $this->newEntity()))
                ->fillFromObject($fleet)
                ->validate($messages = new StringCollection(), true)
                ->hydrate($ormFleet);
        } catch (ValidationFailException) {
            throw (new PersistFailException('Can\t persist fleet'))
                ->setReasons($messages);
        }

        $ormFleet->persist();

        return $this;

    }

    public function fleetHasVehicle(string $idFleet, string $idVehicle): bool
    {

        try {
            $this->getRelation('vehicleInFleet')
                ->getManager()
                ->findOneBy(['idFleet' => $idFleet, 'idVehicle' => $idVehicle]);
        } catch (EmptyResultException) {
            return false;
        }

        return true;

    }

    public function addVehicleToFleet(string $fleetId, string $vehicleId): self
    {

        /** @var VehicleInFleet $vehicleInFleet */
        $vehicleInFleet = $this->getRelation('vehicleInFleet')
            ->getManager()
            ->newEntity();
        $vehicleInFleet->setId($fleetId . $vehicleId);
        $vehicleInFleet->setIdFleet($fleetId);
        $vehicleInFleet->setIdVehicle($vehicleId);

        try {
            FormBuilder::createFromAdapter(new AnnotationAdapter($vehicleInFleet))
                ->validate($messages = new StringCollection(), true);
        } catch (ValidationFailException) {
            throw (new PersistFailException(
                'Can\t persist vehicle #' . $vehicleId . ' in fleet #' . $fleetId
            ))->setReasons($messages);
        }

        $vehicleInFleet->persist();

        return $this;

    }

}