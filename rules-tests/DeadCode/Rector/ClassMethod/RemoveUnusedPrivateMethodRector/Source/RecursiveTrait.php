<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector\Source;

trait RecursiveTrait {
    public function getRecursive(): object {
        return new class () {
            use RecursiveTrait;
        };
    }
}