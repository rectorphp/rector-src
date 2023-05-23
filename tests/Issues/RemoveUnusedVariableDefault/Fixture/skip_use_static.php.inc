<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\RemoveUnusedVariableDefault\Fixture;

class SkipUseStatic
{
    public static function showProgress($thisStep = 1, int $totalSteps = 10)
    {
        static $inProgress = false;

        if ($inProgress !== false && $inProgress <= $thisStep) {
            static::fwrite(STDOUT, "\033[1A");
        }
        $inProgress = $thisStep;

        if ($thisStep !== false) {
            $thisStep   = abs($thisStep);
        }
    }
}

?>
