<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnExprInConstruct\Fixture;

final class SkipNotConstruct
{
    public function run()
    {
        return true;
    }
}
