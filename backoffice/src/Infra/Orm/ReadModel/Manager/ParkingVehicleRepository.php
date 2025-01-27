<?php

namespace Fulll\Infra\Orm\ReadModel\Manager;

use Fulll\App\Gateway\Exception\ParkingVehiculeNotFoundEception;
use Fulll\App\Gateway\Query\ParkingVehicleRepositoryInterface;
use Fulll\Domain\Collection\ParkingVehicleCollection;
use Fulll\Domain\Entity\Location;
use Fulll\Infra\Orm\WriteModel\Entity\ParkingVehicle;
use Small\SwooleEntityManager\EntityManager\AbstractRelationnalManager;
use Small\SwooleEntityManager\EntityManager\Attribute\Connection;
use Small\SwooleEntityManager\EntityManager\Attribute\Entity;
use Small\SwooleEntityManager\EntityManager\Exception\EmptyResultException;

#[Entity(ParkingVehicle::class)]
#[Connection('parking_vehicle', 'reader')]
class ParkingVehicleRepository extends AbstractRelationnalManager
    implements ParkingVehicleRepositoryInterface
{

    public function getParkingVehicleByFleetId(string $idFleet): ParkingVehicleCollection
    {

        try {
            $ormParkingVehicleCollection = $this->findBy(['idFleet' => $idFleet]);
        } catch (EmptyResultException) {
            throw new ParkingVehiculeNotFoundEception('Parking Vehicle not found');
        }

        $parkingVehicleCollection = new ParkingVehicleCollection();
        /** @var \Fulll\Infra\Orm\ReadModel\Entity\ParkingVehicle $ormParkingVehicle */
        foreach ($ormParkingVehicleCollection as $ormParkingVehicle) {
            $parkingVehicle = new \Fulll\Domain\Entity\ParkingVehicle();
            $parkingVehicle->setId($ormParkingVehicle->getId());
            $parkingVehicle->setIdVehicle($ormParkingVehicle->getIdVehicle());
            $parkingVehicle->setIdFleet($ormParkingVehicle->getIdFleet());
            if ($ormParkingVehicle->getLocalization() !== null) {
                $parkingVehicle->setLocation(
                    (new Location())
                        ->setLatitude($ormParkingVehicle->getLocalization()['latitude'])
                        ->setLongitude($ormParkingVehicle->getLocalization()['longitude'])
                        ->setAltitude($ormParkingVehicle->getLocalization()['altitude'])
                );
            }
            $parkingVehicleCollection[] = $parkingVehicle;
        }

        return $parkingVehicleCollection;

    }

}