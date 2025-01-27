<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Fulll\App\Command\AddUserCommand;
use Fulll\App\Command\AddVehicleCommand;
use Fulll\App\Command\ParkVehicleCommand;
use Fulll\App\Command\RegisterVehicleCommand;
use Fulll\App\Gateway\Command\Request\AddUserRequestInterface;
use Fulll\App\Gateway\Command\Request\AddVehicleRequestInterface;
use Fulll\App\Gateway\Command\Request\ParkVehicleRequestInterface;
use Fulll\App\Gateway\Command\Request\RegisterVehicleRequestInterface;
use Fulll\App\Gateway\Command\Response\AddUserResponseInterface;
use Fulll\App\Gateway\Command\Response\AddVehicleResponseInterface;
use Fulll\Domain\Entity\Fleet;
use Fulll\Domain\Entity\Location;
use Fulll\Domain\Entity\User;
use Fulll\Domain\Entity\Vehicle;
use PHPUnit\Framework\Assert;
use Small\CleanApplication\Facade;

class FleetContext implements Context
{

    public User $myself;
    public User $anotherUser;
    public Vehicle $vehicle;
    public Location $location;
    public \Exception $exception;

    #[Given('my fleet')]
    public function givenMyFleet(): void
    {

        /** @var AddUserResponseInterface $response */
        $response = Facade::execute(

            AddUserCommand::class,

            /** @phpstan-ignore-next-line  */
            new readonly class(md5((string)rand(1, 1000000))) implements AddUserRequestInterface
            {

                public function __construct(
                    private string $userId
                ) {}

                public function getUserId(): string
                {
                    return $this->userId;
                }


            }

        );

        $this->myself = $response->getUser();

    }

    #[Given('the fleet of another user')]
    public function theFleetOfAnotherUser(): void
    {

        /** @var AddUserResponseInterface $response */
        $response = Facade::execute(

            AddUserCommand::class,

            /** @phpstan-ignore-next-line  */
            new readonly class(md5((string)rand(1, 1000000))) implements AddUserRequestInterface
            {

                public function __construct(
                    private string $userId
                ) {}

                public function getUserId(): string
                {
                    return $this->userId;
                }


            }

        );

        $this->anotherUser = $response->getUser();

    }

    #[Given('a vehicle')]
    public function aVehicle(): void
    {

        /** @var AddVehicleResponseInterface $response */
        $response = Facade::execute(

            AddVehicleCommand::class,

            /** @phpstan-ignore-next-line  */
            new readonly class(md5((string)rand(1, 50000))) implements AddVehicleRequestInterface
            {

                public function __construct(
                    private string $licensePlate
                ) {}

                public function getLicensePlate(): string
                {
                    return $this->licensePlate;
                }


            }

        );

        $this->vehicle = $response->getVehicle();

    }

    #[Given('a location')]
    public function aLocation(): void
    {

        $this->location = (new Location())
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
            Facade::execute(

                RegisterVehicleCommand::class,

                new readonly class($this->myself, $this->vehicle)
                    implements RegisterVehicleRequestInterface
                {

                    public function __construct(
                        private User    $user,
                        private Vehicle $vehicle
                    ) {}

                    public function getFleet(): Fleet|User
                    {
                        return $this->user;
                    }

                    public function getVehicle(): Vehicle
                    {
                        return $this->vehicle;
                    }


                }

            );
        } catch (\Exception $e) {
            $this->exception = $e;
        }

    }

    #[Given('this vehicle has been registered into the other user\'s fleet')]
    public function thisVehicleHasBeenRegisteredIntoTheOtherUsersFleet(): void
    {

        try {

            Facade::execute(

                RegisterVehicleCommand::class,

                new readonly class($this->anotherUser, $this->vehicle)
                    implements RegisterVehicleRequestInterface
                {

                    public function __construct(
                        private User $user,
                        private Vehicle $vehicle,
                    ) {}

                    public function getFleet(): Fleet|User
                    {
                        return $this->user;
                    }

                    public function getVehicle(): Vehicle
                    {
                        return $this->vehicle;
                    }


                }

            );
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

            Facade::execute(

                ParkVehicleCommand::class,

                new readonly class($this->myself, $this->vehicle)
                    implements ParkVehicleRequestInterface
                {

                    public function __construct(
                        private User $user,
                        private Vehicle $vehicle,
                    ) {}

                    public function getFleet(): Fleet|User
                    {
                        return $this->user;
                    }

                    public function getVehicle(): Vehicle
                    {
                        return $this->vehicle;
                    }


                }

            );
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
