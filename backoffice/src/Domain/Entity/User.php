<?php

namespace Fulll\Domain\Entity;

use Fulll\Domain\Entity\Trait\HasIdentifier;

class User
{

    use HasIdentifier;

    private ?string $username = null;
    private ?string $fleetId = null;
    private ?Fleet $myFleet;

    public function __construct()
    {
        $this->myFleet = new Fleet();
    }

    public static function create(string $username): self
    {

        return ($user = new self())
            ->generateId()
            ->setUsername($username)
            ->setMyFleet(Fleet::create($user->id))
        ;

    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getMyFleet(): ?Fleet
    {
        return $this->myFleet;
    }

    public function setMyFleet(?Fleet $myFleet): self
    {
        $this->myFleet = $myFleet;

        return $this;
    }

}