<?php

namespace Fulll\Infra\Orm\WriteModel\Manager;

use Fulll\App\Gateway\Command\ManagerInterface\ParkingVehicleManagerInterface;
use Fulll\App\Gateway\Exception\ParkingVehiculeNotFoundEception;
use Fulll\Infra\Orm\WriteModel\Entity\ParkingVehicle;
use Small\SwooleEntityManager\EntityManager\AbstractRelationnalManager;
use Small\SwooleEntityManager\EntityManager\Attribute\Connection;
use Small\SwooleEntityManager\EntityManager\Attribute\Entity;
use Small\SwooleEntityManager\EntityManager\Exception\EmptyResultException;

#[Entity(ParkingVehicle::class)]
#[Connection('parking_vehicle', 'writer')]
class ParkingVehicleManager extends AbstractRelationnalManager
    implements ParkingVehicleManagerInterface
{
    public function saveParkingVehicle(\Fulll\Domain\Entity\ParkingVehicle $parkingVehicle): self
    {

        try {
            $ormParkingVehicle = $this->findOneBy([
                'idFleet' => $parkingVehicle->getIdFleet(),
                'idVehicle' => $parkingVehicle->getIdVehicle(),
            ]);
        } catch (EmptyResultException) {
            /** @var ParkingVehicle $ormParkingVehicle */
            $ormParkingVehicle = $this->newEntity();
            $ormParkingVehicle->setId($parkingVehicle->getId());
            $ormParkingVehicle->setIdVehicle($parkingVehicle->getIdVehicle());
            $ormParkingVehicle->setIdFleet($parkingVehicle->getIdFleet());
        }

        $ormParkingVehicle->setLocalization([
            'latitude' => $parkingVehicle->getLocation()->getLatitude(),
            'longitude' => $parkingVehicle->getLocation()->getLongitude(),
            'altitude' => $parkingVehicle->getLocation()->getAltitude(),
        ]);

        $ormParkingVehicle->persist();

        return $this;

    }

    public function getParkingVehicleById(string $id): \Fulll\Domain\Entity\ParkingVehicle
    {

        try {
            $ormParkingVehicle = $this->findOneBy(['id' => $id]);
        } catch (EmptyResultException) {
            throw new ParkingVehiculeNotFoundEception('Parking Vehicle not found');
        }

        $parkingVehicle = new ParkingVehicle();
        $parkingVehicle->setId($ormParkingVehicle->getId());
        $parkingVehicle->setIdVehicle($ormParkingVehicle->getIdVehicle());
        $parkingVehicle->setIdFleet($ormParkingVehicle->getIdFleet());
        $parkingVehicle->setLocalization([
            'latitude' => $ormParkingVehicle->getLocalization()->getLatitude(),
            'longitude' => $ormParkingVehicle->getLocalization()->getLongitude(),
            'altitude' => $ormParkingVehicle->getLocalization()->getAltitude(),
        ]);

        return $parkingVehicle;

    }

}