<?php

namespace Fulll\Infra\Orm\WriteModel\Entity;

use Fulll\Infra\Orm\ReadModel\Collection\VehicleInFleetCollection;
use Fulll\Infra\Orm\WriteModel\Manager\VehicleInFleetManager;
use Small\Forms\Form\Field\Type\StringType;
use Small\Forms\ValidationRule\ValidateNotEmpty;
use Small\SwooleEntityManager\Entity\AbstractEntity;
use Small\SwooleEntityManager\Entity\Attribute\OrmEntity;
use Small\SwooleEntityManager\Entity\Attribute\PrimaryKey;
use Small\SwooleEntityManager\Entity\Attribute\ToMany;

#[OrmEntity]
class Fleet extends AbstractEntity
{

    #[PrimaryKey]
    #[StringType]
    #[ValidateNotEmpty]
    protected ?string $id = null;
    #[ToMany(VehicleInFleetManager::class, ['id' => 'idFleet'])]
    protected VehicleInFleetCollection $vehicleInFleet;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getVehicleInFleet(): VehicleInFleetCollection
    {
        return $this->vehicleInFleet;
    }

    public function setVehicleInFleet(VehicleInFleet $vehicleInFleet): self
    {
        $this->vehicleInFleet = $vehicleInFleet;
        return $this;
    }

}