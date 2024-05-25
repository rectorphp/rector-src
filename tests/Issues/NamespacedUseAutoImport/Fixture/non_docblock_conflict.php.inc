<?php

namespace Rector\Tests\Issues\NamespacedUseAutoImport\Fixture;

use Some\Class_;
use PhpParser\Node;

final class SkipNonDocblockConflictLastName
{
    public function run(Node\Stmt\Class_ $param): void
    {
        new Class_();
    }


}