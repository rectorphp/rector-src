<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnExprInConstructRector\Fixture;

final class SkipReturnNoExpr
{
    public function __construct()
    {
        if (rand(0, 1)) {
            $this->init();
            return;
        }

        $this->execute();
    }
}
