<?php

namespace Rector\Tests\DeadCode\Rector\Node\RemoveNonExistingVarAnnotationRector\Fixture;

final class SkipStaticVariableInMethod
{
    public function run(int $param): int
    {
        /**
         * Rector should not remove this @var annotation.
         *
         * @var array<int,int>
         */
        static $cached_result = [];

        if (!isset($cached_result[$param])) {
            $cached_result[$param] = doExpensiveCalculation($param);
        }

        return $cached_result[$param];
    }
}
