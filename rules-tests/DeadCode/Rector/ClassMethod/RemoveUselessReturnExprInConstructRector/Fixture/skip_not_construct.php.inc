<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnExprInConstructRector\Fixture;

final class SkipNotConstruct
{
    public function run()
    {
        return true;
    }
}
