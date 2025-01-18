<?php

namespace FizBuz\Helper;

class CheckFormat
{

    /**
     * Check if string represent an integer
     * @param string $string
     * @return bool
     */
    public static function isStringIsInt(string $string): bool
    {

        return preg_match('/^-?\d+$/', $string) === 1;

    }

}