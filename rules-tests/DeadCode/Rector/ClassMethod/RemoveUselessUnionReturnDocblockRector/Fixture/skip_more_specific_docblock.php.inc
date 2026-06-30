<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessUnionReturnDocblockRector\Fixture;

final class SkipMoreSpecificDocblock
{
    /**
     * @return \DateTime|\DateTimeImmutable
     */
    public function run(): object
    {
    }
}
