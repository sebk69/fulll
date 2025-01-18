<?php

use FizBuz\Business\Exception\FizBuzException;
use FizBuz\Business\FizBuz;

test('Test no result', function () {

    foreach ([1, 2, 4, 7, 8, 11, 13, 14] as $number) {

        $fail = false;
        try {
            new FizBuz($number)->run();
        } catch (\FizBuz\Business\Exception\NoneResultException) {
            $fail = true;
        }

        expect($fail)->toBeTrue();

    }

});

test('Test Fiz', function () {

    foreach ([3, 6, 9, 12] as $number) {

        expect(new FizBuz($number)->run())->toBe(\FizBuz\Business\Enum\FizBuzResult::fiz);

    }

});

test('Test Buz', function () {

    foreach ([5, 10] as $number) {

        expect(new FizBuz($number)->run())->toBe(\FizBuz\Business\Enum\FizBuzResult::buz);

    }

});

test('Test FizBuz', function () {

    foreach ([15, 30] as $number) {

        expect(new FizBuz($number)->run())->toBe(\FizBuz\Business\Enum\FizBuzResult::fizBuz);

    }

});

test('Fail', function () {

    new FizBuz(0);

})->throws(\FizBuz\Business\Exception\BadRequestException::class);
