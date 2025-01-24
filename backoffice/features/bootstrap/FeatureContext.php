<?php

declare(strict_types=1);

use Behat\Behat\Context\Context;
use Behat\Step\Given;
use Behat\Step\When;
use Behat\Step\Then;
use Fulll\Domain\Entity\User;
use Fulll\Domain\Entity\Vehicle;
use Fulll\App\Command\RegisterVehicle;
use Fulll\Domain\Entity\Location;
use PHPUnit\Framework\Assert;

class FeatureContext implements Context
{

    public User $myself;
    public User $anotherUser;
    public Vehicle $vehicle;
    public Location $location;
    public \Exception $exception;

    #[Given('my fleet')]
    public function givenMyFleet(): void
    {

        $this->myself = User::create('me');

    }

    #[Given('the fleet of another user')]
    public function theFleetOfAnotherUser(): void
    {

        $this->anotherUser = User::create('another');

    }

    #[Given('a vehicle')]
    public function aVehicle(): void
    {

        $this->vehicle = Vehicle::create(md5((string)rand(1, 50000)));

    }

    #[Given('a location')]
    public function aLocation(): void
    {

        $this->location = new Location()
            ->setLatitude(43.9699)
            ->setLongitude(4.8600)
            ->setAltitude(98);

    }

    #[When('I register this vehicle into my fleet')]
    #[When('I try to register this vehicle into my fleet')]
    #[Given('I have registered this vehicle into my fleet')]
    public function iRegisterThisVehicle(): void
    {

        try {
            new RegisterVehicle()
                ->execute($this->myself, $this->vehicle);
        } catch (\Exception $e) {
            $this->exception = $e;
        }

    }

    #[Given('this vehicle has been registered into the other user\'s fleet')]
    public function thisVehicleHasBeenRegisteredIntoTheOtherUsersFleet(): void
    {

        try {
            new RegisterVehicle()
                ->execute($this->anotherUser, $this->vehicle);
        } catch (\Exception $e) {
            $this->exception = $e;
        }

    }

    #[Then('this vehicle should be part of my vehicle fleet')]
    function thisVehicleShouldBePartOfMyVehicleFleet(): void
    {

        $this
            ->myself
            ->getMyFleet()
            ->getVehicles()
            ->getVehicleByLicensePlate(
                $this->
                    vehicle
                    ->getLicensePlate()
            );

    }

    #[Then('I should be informed this this vehicle has already been registered into my fleet')]
    function iShouldBeInformedThisVehicleHasAlreadyBeenRegisteredInMyVehicleFleet(): void
    {

        Assert::assertInstanceOf(
            \Fulll\Domain\Entity\Exception\VehicleAlreadyRegistedException::class,
            $this->exception
        );

    }

    #[When('I park my vehicle at this location')]
    #[Given('my vehicle has been parked into this location')]
    #[When('I try to park my vehicle at this location')]
    function iParkMyVehicleAtThisLocation(): void
    {

        try {
            $this->vehicle->setLocation($this->location);
            new \Fulll\App\Command\ParkVehicle()
                ->execute($this->myself, $this->vehicle);
        } catch (\Exception $e) {
            $this->exception = $e;
        }

    }

    #[Then('the known location of my vehicle should verify this location')]
    function theKnownLocationOfMyVehicleShouldVerifyThisLocation(): void
    {

        Assert::assertEquals(
            $this
                ->location
                ->getLatitude(),
            $this
                ->vehicle
                ->getLocation()
                ->getLatitude(),
        );

        Assert::assertEquals(
            $this
                ->location
                ->getLongitude(),
            $this
                ->vehicle
                ->getLocation()
                ->getLongitude(),
        );

        Assert::assertEquals(
            $this
                ->location
                ->getAltitude(),
            $this
                ->vehicle
                ->getLocation()
                ->getAltitude(),
        );

    }

    #[Then('I should be informed that my vehicle is already parked at this location')]
    function iShouldBeInformedThatMyVehicleIsAlreadyParkedAtThisLocation(): void
    {

        Assert::assertInstanceOf(
            \Fulll\App\Command\Exception\AlreadyParkedHereException::class,
            $this->exception
        );

    }

}
