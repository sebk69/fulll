<?php

namespace Fulll\Infra\Orm\WriteModel\Entity;

use Small\Forms\Form\Field\Type\ArrayType;
use Small\Forms\Form\Field\Type\StringType;
use Small\Forms\ValidationRule\ValidateNotEmpty;
use Small\SwooleEntityManager\Entity\Attribute\Field;
use Small\SwooleEntityManager\Entity\Attribute\OrmEntity;
use Small\SwooleEntityManager\Entity\Attribute\PrimaryKey;
use Small\SwooleEntityManager\Entity\Enum\FieldValueType;

#[OrmEntity]
class ParkingVehicle
{

    #[PrimaryKey]
    #[StringType]
    #[ValidateNotEmpty]
    protected ?string $id = null;
    #[Field(FieldValueType::string)]
    #[StringType]
    #[ValidateNotEmpty]
    protected ?string $idVehicle = null;
    #[Field(FieldValueType::string)]
    #[StringType]
    #[ValidateNotEmpty]
    protected ?string $idFleet = null;
    #[Field(FieldValueType::json)]
    #[ArrayType(new StringType)]
    protected array $localization = [];

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): ParkingVehicle
    {
        $this->id = $id;
        return $this;
    }

    public function getIdVehicle(): ?string
    {
        return $this->idVehicle;
    }

    public function setIdVehicle(?string $idVehicle): ParkingVehicle
    {
        $this->idVehicle = $idVehicle;
        return $this;
    }

    public function getIdFleet(): ?string
    {
        return $this->idFleet;
    }

    public function setIdFleet(?string $idFleet): ParkingVehicle
    {
        $this->idFleet = $idFleet;
        return $this;
    }

    public function getLocalization(): array
    {
        return $this->localization;
    }

    public function setLocalization(array $localization): ParkingVehicle
    {
        $this->localization = $localization;
        return $this;
    }

}