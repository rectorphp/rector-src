<?php

namespace Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Fixture;

use Rector\NodeAnalyzer\ClassAnalyzer;

final class SkipChangePromotedDuplicateType
{
    public function __construct(
        private ClassAnalyzer $classAnalyzer1,
        private ClassAnalyzer $classAnalyzer2
    ) {
    }
}
