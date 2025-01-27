<?php

namespace Fulll\Infra\Orm\ReadModel\Entity;

use Fulll\Infra\Orm\ReadModel\Collection\FleetCollection;
use Fulll\Infra\Orm\ReadModel\Collection\VehicleInFleetCollection;
use Fulll\Infra\Orm\ReadModel\Manager\VehicleInFleetRepository;
use Small\Forms\Form\Field\Type\StringType;
use Small\Forms\ValidationRule\ValidateNotEmpty;
use Small\SwooleEntityManager\Entity\AbstractEntity;
use Small\SwooleEntityManager\Entity\Attribute\Collection;
use Small\SwooleEntityManager\Entity\Attribute\OrmEntity;
use Small\SwooleEntityManager\Entity\Attribute\PrimaryKey;
use Small\SwooleEntityManager\Entity\Attribute\ToMany;
use Small\SwooleEntityManager\EntityManager\AbstractManager;
use function Termwind\parse;

#[OrmEntity]
#[Collection(FleetCollection::class)]
class Fleet extends AbstractEntity
{

    #[PrimaryKey]
    #[StringType]
    #[ValidateNotEmpty]
    protected ?string $id = null;
    #[ToMany(VehicleInFleetRepository::class, ['id' => 'idFleet'])]
    protected VehicleInFleetCollection $vehicleInFleet;

    public function __construct(AbstractManager $manager)
    {
        $this->vehicleInFleet = new VehicleInFleetCollection();
        parent::__construct($manager);
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): Fleet
    {
        $this->id = $id;
        return $this;
    }

    public function getVehicleInFleet(): VehicleInFleetCollection
    {
        return $this->vehicleInFleet;
    }

    public function setVehicleInFleet(VehicleInFleet $vehicleInFleet): Fleet
    {
        $this->vehicleInFleet = $vehicleInFleet;
        return $this;
    }

}