<?php

namespace Rector\Tests\Issues\NamespacedUseAutoImport\Fixture;

use Some\Class_;
use PhpParser\Node;

final class SkipConflictLastNameUsed
{
    /**
     * @param Node\Stmt\Class_ $param
     */
    public function run($param): void
    {
        new Class_();
    }
}
