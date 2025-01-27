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
class Vehicle extends AbstractEntity
{

    #[PrimaryKey]
    #[StringType]
    #[ValidateNotEmpty]
    protected ?string $id = null;
    #[Field(FieldValueType::string)]
    #[StringType]
    #[ValidateNotEmpty]
    protected ?string $licensePlate = null;
    #[Field(FieldValueType::json, JsonFormatType::array)]
    #[ArrayType(new StringType)]
    protected array|null $localization = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): Vehicle
    {
        $this->id = $id;
        return $this;
    }

    public function getLicensePlate(): ?string
    {
        return $this->licensePlate;
    }

    public function setLicensePlate(?string $licensePlate): Vehicle
    {
        $this->licensePlate = $licensePlate;
        return $this;
    }

    public function getLocalization(): array|null
    {
        return $this->localization;
    }

    public function setLocalization(array $localization): Vehicle
    {
        $this->localization = $localization;
        return $this;
    }

}