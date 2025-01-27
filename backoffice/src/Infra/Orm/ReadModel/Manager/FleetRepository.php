<?php

namespace Fulll\Infra\Orm\ReadModel\Manager;

use Fulll\App\Gateway\Exception\FleetNotFoundException;
use Fulll\App\Gateway\Query\FleetRepositoryInterface;
use Fulll\Domain\Collection\FleetVehicleCollection;
use Fulll\Infra\Orm\ReadModel\Collection\VehicleInFleetCollection;
use Fulll\Infra\Orm\ReadModel\Entity\Fleet;
use Fulll\Infra\Orm\ReadModel\Entity\VehicleInFleet;
use Small\SwooleEntityManager\EntityManager\AbstractRelationnalManager;
use Small\SwooleEntityManager\EntityManager\Attribute\Connection;
use Small\SwooleEntityManager\EntityManager\Attribute\Entity;
use Small\SwooleEntityManager\EntityManager\Exception\EmptyResultException;

#[Entity(Fleet::class)]
#[Connection('fleet', 'reader')]
class FleetRepository extends AbstractRelationnalManager
    implements FleetRepositoryInterface
{
    public function getFleetById(string $fleetId): \Fulll\Domain\Entity\Fleet
    {

        try {
            /** @var Fleet $ormFleet */
            $ormFleet = $this->findOneBy(['id' => $fleetId]);
        } catch (EmptyResultException) {
            throw new FleetNotFoundException('Fleet #' . $fleetId . ' not found');
        }

        $fleet = (new \Fulll\Domain\Entity\Fleet())
            ->setId($ormFleet->getId());

        return $fleet;

    }

    public function getVehiclesInFleet(\Fulll\Domain\Entity\Fleet $fleet): FleetVehicleCollection
    {

        /** @var VehicleInFleetCollection $ormVehicleInFleetCollection */
        /** @phpstan-ignore-next-line  */
        $ormVehicleInFleetCollection = $this
            ->toManies['vehicleInFleet']
            ->getManager()
            ->findBy(['idFleet' => $fleet->getId()]);

        $fleetVehicles = new FleetVehicleCollection();
        $ormVehicleInFleetCollection->map(function (int $i, VehicleInFleet $ormVehicleInFleet) use($fleetVehicles) {

            /** @phpstan-ignore-next-line  */
            $vehicle = $this->getRelation('vehicleInFleet')
                ->getManager()
                ->getRelation('vehicle')
                ->getManager()
                ->getVehicleById($ormVehicleInFleet->getIdVehicle());

            $fleetVehicles->push($vehicle);

        });

        return $fleetVehicles;

    }

}