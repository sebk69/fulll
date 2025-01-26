<?php

namespace Fulll\Infra\Orm\WriteModel\Entity;

use Small\Forms\Form\Field\Type\StringType;
use Small\Forms\ValidationRule\ValidateNotEmpty;
use Small\Forms\ValidationRule\ValidateNumberCharsBetween;
use Small\SwooleEntityManager\Entity\AbstractEntity;
use Small\SwooleEntityManager\Entity\Attribute\Field;
use Small\SwooleEntityManager\Entity\Attribute\OrmEntity;
use Small\SwooleEntityManager\Entity\Attribute\PrimaryKey;
use Small\SwooleEntityManager\Entity\Enum\FieldValueType;

#[OrmEntity]
class User extends AbstractEntity
{

    #[PrimaryKey]
    #[ValidateNotEmpty]
    #[StringType]
    protected ?string $id = null;

    #[Field(FieldValueType::string)]
    #[StringType]
    #[ValidateNumberCharsBetween(1, 64)]
    protected ?string $username = null;
    #[Field(FieldValueType::string)]
    #[StringType]
    #[ValidateNotEmpty]
    protected ?string $fleetId = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): User
    {
        $this->id = $id;
        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): User
    {
        $this->username = $username;
        return $this;
    }

    public function getFleetId(): ?string
    {
        return $this->fleetId;
    }

    public function setFleetId(?string $fleetId): User
    {
        $this->fleetId = $fleetId;
        return $this;
    }

}