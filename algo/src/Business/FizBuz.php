<?php

namespace FizBuz\Business;

use FizBuz\Business\Enum\FizBuzResult;
use FizBuz\Business\Exception\BadRequestException;
use FizBuz\Business\Exception\NoneResultException;

class FizBuz
{

    /**
     * Constructor
     * @param int $number
     * @throws BadRequestException
     */
    public function __construct(
        public readonly int $number
    ) {

        if ($this->number < 1) {
            throw new BadRequestException('Number must be greater than 1 (' . $this->number . ')');
        }

    }


    /**
     * Run FizBuz
     * @return FizBuzResult
     * @throws NoneResultException
     */
    public function run(): FizBuzResult
    {

        // Divisible by 3 and 5
        if (
            $this->number % 3 === 0 &&
            $this->number % 5 === 0
        ) {

            return FizBuzResult::fizBuz;
        }

        // Divisible by 3
        if (
            $this->number % 3 === 0
        ) {
            return FizBuzResult::fiz;
        }

        // Divisible by 5
        if (
            $this->number % 5 === 0
        ) {
            return FizBuzResult::buz;
        }

        // Else, throw exception
        throw new NoneResultException('No result');

    }

}