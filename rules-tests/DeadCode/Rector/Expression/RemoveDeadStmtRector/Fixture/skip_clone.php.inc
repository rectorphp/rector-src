<?php

namespace Rector\Tests\DeadCode\Rector\Expression\RemoveDeadStmtRector\Fixture;

function skipClone($object)
{
    $a = clone $object;
    clone $object;
}
