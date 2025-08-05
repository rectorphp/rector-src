<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodParameterRector\Fixture;

final class SkipGetDefinedVars
{
    private function example($value): array
    {
        return get_defined_vars();
    }
}
