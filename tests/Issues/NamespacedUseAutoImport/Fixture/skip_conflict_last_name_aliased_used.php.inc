<?php

namespace Rector\Tests\Issues\NamespacedUseAutoImport\Fixture;

use Some\Class_ as ClassOrig;
use PhpParser\Node;

final class SkipConflictLastNameAliasedUsed
{
    /**
     * @param Node\Stmt\Class_ $param
     */
    public function run($param): void
    {
        new ClassOrig();
    }
}
