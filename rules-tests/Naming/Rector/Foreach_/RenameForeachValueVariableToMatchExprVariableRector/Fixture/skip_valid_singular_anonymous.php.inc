<?php

namespace Rector\Tests\Naming\Rector\Foreach_\RenameForeachValueVariableToMatchExprVariableRector\Fixture;

class SkipValidSingularAnonymous
{
    public function run(array $anonymousClassNodes)
    {
        foreach ($anonymousClassNodes as $anonymousClassNode) {
            echo $anonymousClassNode;
        }
    }
}
