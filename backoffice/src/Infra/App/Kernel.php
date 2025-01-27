<?php

namespace App;

use Fulll\App\Gateway\Query\FleetRepositoryInterface;
use Fulll\App\Gateway\Query\VehicleRepositoryInterface;
use Fulll\Infra\Orm\ReadModel\Manager\FleetRepository;
use Fulll\Infra\Orm\ReadModel\Manager\ParkingVehicleRepository;
use Fulll\Infra\Orm\ReadModel\Manager\VehicleRepository;
use Fulll\Infra\Orm\WriteModel\Manager\FleetManager;
use Fulll\Infra\Orm\WriteModel\Manager\ParkingVehicleManager;
use Fulll\Infra\Orm\WriteModel\Manager\UserManager;
use Fulll\Infra\Orm\WriteModel\Manager\VehicleManager;
use Small\CleanApplication\Facade;
use Small\SwooleEntityManagerBundle\Contract\EntityManagerFactoryInterface;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function boot(): void
    {

        parent::boot();

        Facade::setParameter('userManager',
            /** @phpstan-ignore-next-line  */
            $this->getContainer()
                ->get(EntityManagerFactoryInterface::class)
                ->get(UserManager::class)
        );

        Facade::setParameter('fleetManager',
            /** @phpstan-ignore-next-line  */
            $this->getContainer()
                ->get(EntityManagerFactoryInterface::class)
                ->get(FleetManager::class)
        );

        Facade::setParameter('vehicleManager',
            /** @phpstan-ignore-next-line  */
            $this->getContainer()
                ->get(EntityManagerFactoryInterface::class)
                ->get(VehicleManager::class)
        );

        Facade::setParameter('parkingVehicleManager',
            /** @phpstan-ignore-next-line  */
            $this->getContainer()
                ->get(EntityManagerFactoryInterface::class)
                ->get(ParkingVehicleManager::class)
        );

        Facade::setParameter('fleetRepository',
            /** @phpstan-ignore-next-line  */
            $this->getContainer()
                ->get(EntityManagerFactoryInterface::class)
                ->get(FleetRepository::class)
        );

        Facade::setParameter('vehicleRepository',
            /** @phpstan-ignore-next-line  */
            $this->getContainer()
                ->get(EntityManagerFactoryInterface::class)
                ->get(VehicleRepository::class)
        );

        Facade::setParameter('parkingVehicleRepository',
            /** @phpstan-ignore-next-line  */
            $this->getContainer()
                ->get(EntityManagerFactoryInterface::class)
                ->get(ParkingVehicleRepository::class)
        );

    }

}
