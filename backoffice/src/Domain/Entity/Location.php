<?php

namespace Fulll\Domain\Entity;

use Fulll\Domain\Entity\Exception\BadLocationException;

class Location
{

    /** Unit : degree */
    private ?float $latitude = null;
    /** Unit : degree */
    private ?float $longitude = null;
    /** Unit : feet */
    private ?float $altitude = null;

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {

        if ($latitude < -90.0 || $latitude > 90.0) {
            throw new BadLocationException("Latitude must be between -90 and 90 degrees.");
        }

        $this->latitude = $latitude;

        return $this;

    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {

        if ($longitude < -180.0 || $longitude > 180.0) {
            throw new BadLocationException("Longitude must be between -180 and 180 degrees.");
        }

        $this->longitude = $longitude;

        return $this;

    }

    public function getAltitude(): ?float
    {
        return $this->altitude;
    }

    public function setAltitude(?float $altitude): self
    {
        $this->altitude = $altitude;

        return $this;
    }

}