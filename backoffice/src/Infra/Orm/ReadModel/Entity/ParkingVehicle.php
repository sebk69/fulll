<?php

namespace Fulll\Infra\Orm\ReadModel\Entity;

use Small\Forms\Form\Field\Type\ArrayType;
use Small\Forms\Form\Field\Type\StringType;
use Small\Forms\ValidationRule\ValidateNotEmpty;
use Small\SwooleEntityManager\Entity\AbstractEntity;
use Small\SwooleEntityManager\Entity\Attribute\Field;
use Small\SwooleEntityManager\Entity\Attribute\OrmEntity;
use Small\SwooleEntityManager\Entity\Attribute\PrimaryKey;
use Small\SwooleEntityManager\Entity\Enum\FieldValueType;
use Small\SwooleEntityManager\EntityManager\Enum\JsonFormatType;

#[OrmEntity]
class ParkingVehicle extends AbstractEntity
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
    /** @var float[]|null  */
    #[Field(FieldValueType::json, JsonFormatType::array)]
    #[ArrayType(new StringType)]
    protected array|null $localization = null;

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

    /**
     * @return float[]|null
     */
    public function getLocalization(): array|null
    {
        return $this->localization;
    }

    /**
     * @param float[] $localization
     * @return $this
     */
    public function setLocalization(array $localization): ParkingVehicle
    {
        $this->localization = $localization;
        return $this;
    }

}