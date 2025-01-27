<?php

namespace Fulll\Infra\Orm\WriteModel\Manager;

use Fulll\App\Gateway\Command\ManagerInterface\ParkingVehicleManagerInterface;
use Fulll\App\Gateway\Exception\ParkingVehiculeNotFoundEception;
use Fulll\Infra\Orm\WriteModel\Entity\ParkingVehicle;
use Fulll\Domain\Entity\Location;
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
            /** @var ParkingVehicle $ormParkingVehicle */
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

        if ($parkingVehicle->getLocation() !== null) {
            /** @phpstan-ignore-next-line  */
            $ormParkingVehicle->setLocalization([
                'latitude' => $parkingVehicle->getLocation()->getLatitude(),
                'longitude' => $parkingVehicle->getLocation()->getLongitude(),
                'altitude' => $parkingVehicle->getLocation()->getAltitude(),
            ]);
        }

        $ormParkingVehicle->persist();

        return $this;

    }

    public function getParkingVehicleById(string $id): \Fulll\Domain\Entity\ParkingVehicle
    {

        try {
            /** @var ParkingVehicle $ormParkingVehicle */
            $ormParkingVehicle = $this->findOneBy(['id' => $id]);
        } catch (EmptyResultException) {
            throw new ParkingVehiculeNotFoundEception('Parking Vehicle not found');
        }

        $parkingVehicle = new \Fulll\Domain\Entity\ParkingVehicle();
        $parkingVehicle->setId($ormParkingVehicle->getId());
        $parkingVehicle->setIdVehicle($ormParkingVehicle->getIdVehicle());
        $parkingVehicle->setIdFleet($ormParkingVehicle->getIdFleet());
        if ($ormParkingVehicle->getLocalization() != null) {
            $parkingVehicle->setLocation((new Location())
                ->setLatitude($ormParkingVehicle->getLocalization()['latitude'])
                ->setLongitude($ormParkingVehicle->getLocalization()['longitude'])
                ->setAltitude($ormParkingVehicle->getLocalization()['altitude'])
            );
        }

        return $parkingVehicle;

    }

}