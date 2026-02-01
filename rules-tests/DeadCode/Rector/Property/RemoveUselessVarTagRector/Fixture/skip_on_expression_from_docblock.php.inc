<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Fixture;

use DateTime;
use Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Source\SomeReturnDocblock;

$obj = new SomeReturnDocblock();
/** @var DateTime $return */
$return = $obj->get();
