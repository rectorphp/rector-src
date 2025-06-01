<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveDefaultArgumentFromParentCallRector\Fixture;

final class SkipNoExtends
{
    final public function __construct(string $differentParam)
    {
        init($differentParam);

        parent::__construct([]);
    }
}
