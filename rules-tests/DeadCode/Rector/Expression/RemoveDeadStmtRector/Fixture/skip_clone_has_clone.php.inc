<?php

namespace Rector\Tests\DeadCode\Rector\Expression\RemoveDeadStmtRector\Fixture;

final class SkipCloneHasClone
{
    public function __clone()
    {
        throw new \RuntimeException('clone is not allowed');
    }
}

$obj = new SkipCloneHasClone();
clone $obj;
