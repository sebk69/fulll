<?php

namespace Fulll\Infra\Orm\ReadModel\Entity;

use Fulll\Domain\Entity\Trait\HasIdentifier;
use Fulll\Infra\Orm\ReadModel\Collection\VehicleInFleetCollection;
use Fulll\Infra\Orm\ReadModel\Manager\VehicleRepository;
use Small\Forms\Form\Field\Type\StringType;
use Small\Forms\ValidationRule\ValidateNotEmpty;
use Small\SwooleEntityManager\Entity\AbstractEntity;
use Small\SwooleEntityManager\Entity\Attribute\Collection;
use Small\SwooleEntityManager\Entity\Attribute\Field;
use Small\SwooleEntityManager\Entity\Attribute\OrmEntity;
use Small\SwooleEntityManager\Entity\Attribute\PrimaryKey;
use Small\SwooleEntityManager\Entity\Enum\FieldValueType;
use Small\SwooleEntityManager\Entity\Attribute\ToOne;

#[OrmEntity]
#[Collection(VehicleInFleetCollection::class)]
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
    #[ToOne(VehicleRepository::class, ['idVehicle' => 'id'])]
    protected Vehicle $vehicle;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getIdVehicle(): ?string
    {
        return $this->idVehicle;
    }

    public function getIdFleet(): ?string
    {
        return $this->idFleet;
    }

    public function getVehicle(): ?Vehicle
    {
        return $this->vehicle;
    }

}