<?php

namespace Rector\Tests\CodeQuality\Rector\Expression\TernaryFalseExpressionToIfRector\Fixture;

final class SkipIncrementVariableOnElse
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

    public function run2($id)
    {
        $success = $failed = 0;
        ! $id ? ++$failed : ++$success;

        if ($success > $failed) {
            return $success;
        }

        return $failed;
    }
}