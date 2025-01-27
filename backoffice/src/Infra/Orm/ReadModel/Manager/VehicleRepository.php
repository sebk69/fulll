<?php

namespace Fulll\Infra\Orm\ReadModel\Manager;

use Fulll\App\Gateway\Command\ManagerInterface\FleetManagerInterface;
use Fulll\App\Gateway\Command\ManagerInterface\VehicleManagerInterface;
use Fulll\App\Gateway\Exception\VehicleNotFoundException;
use Fulll\App\Gateway\Query\VehicleRepositoryInterface;
use Fulll\Domain\Entity\Location;
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
use Small\SwooleEntityManager\EntityManager\Exception\EmptyResultException;

#[Entity(Vehicle::class)]
#[Connection('vehicle', 'reader')]
class VehicleRepository extends AbstractRelationnalManager
    implements VehicleRepositoryInterface
{

    public function getVehicleByLicensePlate(string $licensePlate): \Fulll\Domain\Entity\Vehicle
    {

        try {
            /** @var Vehicle $ormVehicle */
            $ormVehicle = $this->findOneBy(['licensePlate' => $licensePlate]);
        } catch (EmptyResultException) {
            throw new VehicleNotFoundException('Vehicle not found');
        }

        $vehicle = new \Fulll\Domain\Entity\Vehicle();
        $vehicle->setId($ormVehicle->getId());
        $vehicle->setLicensePlate($licensePlate);
        $vehicle->setLocation(
            empty($ormVehicle->getLocalization())
                ? null
                : (new Location())
                    ->setLatitude($ormVehicle->getLocalization()['latitude'])
                    ->setLongitude($ormVehicle->getLocalization()['longitude'])
                    ->setAltitude($ormVehicle->getLocalization()['altitude'])
        );

        return $vehicle;

    }

    public function getVehicleById(string $id): \Fulll\Domain\Entity\Vehicle
    {

        try {
            /** @var Vehicle $ormVehicle */
            $ormVehicle = $this->findOneBy(['id' => $id]);
        } catch (EmptyResultException) {
            throw new VehicleNotFoundException('Vehicle not found');
        }

        $vehicle = new \Fulll\Domain\Entity\Vehicle();
        $vehicle->setId($ormVehicle->getId());
        $vehicle->setLicensePlate($ormVehicle->getLicensePlate());
        $vehicle->setLocation(
            empty($ormVehicle->getLocalization())
                ? null
                : (new Location())
                    ->setLatitude($ormVehicle->getLocalization()['latitude'])
                    ->setLongitude($ormVehicle->getLocalization()['longitude'])
                    ->setAltitude($ormVehicle->getLocalization()['altitude'])
        );

        return $vehicle;

    }

}