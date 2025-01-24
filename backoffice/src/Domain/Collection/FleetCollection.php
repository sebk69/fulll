<?php

namespace Fulll\Domain\Collection;

use Fulll\Domain\Collection\Attribute\CheckItemClass;
use Fulll\Domain\Entity\Fleet;

/**
 * @method Fleet current()
 * @method Fleet offsetGet(mixed $offset)
 */
#[CheckItemClass(Fleet::class)]
class FleetCollection extends Collection
{

}