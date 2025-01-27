<?php

namespace Fulll\Domain\Entity;

use Fulll\Domain\Entity\Trait\HasIdentifier;

class User
{

    use HasIdentifier;

    private ?string $fleetId = null;
    private ?Fleet $myFleet;

    public function __construct()
    {
        $this->myFleet = new Fleet();
    }

    public static function create(string $userId): self
    {

        return ($user = new self())
            ->setId($userId)
            ->setMyFleet(Fleet::create())
        ;

    }

    public function getMyFleet(): ?Fleet
    {
        return $this->myFleet;
    }

    public function setMyFleet(?Fleet $myFleet): self
    {

        $this->fleetId = $myFleet?->getId();
        $this->myFleet = $myFleet;

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