<?php

namespace Fulll\Infra\Orm\WriteModel\Entity;

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
    //#[ToMany(Vehicle)]
    //protected VehicleInFleet $vehicleInFleet;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): Fleet
    {
        $this->id = $id;
        return $this;
    }

    public function getVehicleInFleet(): VehicleInFleet
    {
        return $this->vehicleInFleet;
    }

    public function setVehicleInFleet(VehicleInFleet $vehicleInFleet): Fleet
    {
        $this->vehicleInFleet = $vehicleInFleet;
        return $this;
    }

}