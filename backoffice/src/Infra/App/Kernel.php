<?php

namespace App;

use Fulll\Infra\Orm\WriteModel\Manager\FleetManager;
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
            $this->getContainer()
                ->get(EntityManagerFactoryInterface::class)
                ->get(UserManager::class)
        );

        Facade::setParameter('fleetManager',
            $this->getContainer()
                ->get(EntityManagerFactoryInterface::class)
                ->get(FleetManager::class)
        );

        Facade::setParameter('vehicleManager',
            $this->getContainer()
                ->get(EntityManagerFactoryInterface::class)
                ->get(VehicleManager::class)
        );

    }

}
