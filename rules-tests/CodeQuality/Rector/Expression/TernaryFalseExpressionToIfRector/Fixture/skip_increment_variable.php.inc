<?php

namespace Rector\Tests\CodeQuality\Rector\Expression\TernaryFalseExpressionToIfRector\Fixture;

final class SkipIncrementVariable
{
    public function run($id)
    {
        $success = $failed = 0;
        $id ? ++$success : ++$failed;

        if ($success > $failed) {
            return $success;
        }

        return $failed;
    }
}