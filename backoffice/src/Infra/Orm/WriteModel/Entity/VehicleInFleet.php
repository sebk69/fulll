<?php

namespace Fulll\Infra\Orm\WriteModel\Entity;

use Fulll\Domain\Entity\Trait\HasIdentifier;
use Small\Forms\Form\Field\Type\StringType;
use Small\Forms\ValidationRule\ValidateNotEmpty;
use Small\SwooleEntityManager\Entity\AbstractEntity;
use Small\SwooleEntityManager\Entity\Attribute\Field;
use Small\SwooleEntityManager\Entity\Attribute\OrmEntity;
use Small\SwooleEntityManager\Entity\Attribute\PrimaryKey;
use Small\SwooleEntityManager\Entity\Enum\FieldValueType;

#[OrmEntity]
class VehicleInFleet extends AbstractEntity
{

    use HasIdentifier;

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

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): VehicleInFleet
    {
        $this->id = $id;
        return $this;
    }

    public function getIdVehicle(): ?string
    {
        return $this->idVehicle;
    }

    public function setIdVehicle(?string $idVehicle): VehicleInFleet
    {
        $this->idVehicle = $idVehicle;
        return $this;
    }

    public function getIdFleet(): ?string
    {
        return $this->idFleet;
    }

    public function setIdFleet(?string $idFleet): VehicleInFleet
    {
        $this->idFleet = $idFleet;
        return $this;
    }

}