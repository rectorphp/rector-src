<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessUnionReturnDocblockRector\Fixture;

final class SkipDescription
{
    /**
     * @return bool|mixed|string the escaped value
     */
    public function run(): false|string
    {
    }
}
