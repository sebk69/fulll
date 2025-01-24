<?php

namespace Fulll\Domain\Collection\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
class CheckItemClass
{

    public function __construct(
        public string $classname,
    ) {}

}