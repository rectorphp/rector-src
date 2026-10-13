<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessUnionReturnDocblockRector\Fixture;

/**
 * @return static|null
 */
function skipStaticFunction(): ?SomeStaticInterface
{
    return null;
}
