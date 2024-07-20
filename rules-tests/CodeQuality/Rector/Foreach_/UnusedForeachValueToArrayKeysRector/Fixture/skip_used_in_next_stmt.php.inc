<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\UnusedForeachValueToArrayKeysRector\Fixture;

final class SkipUsedInNextStmt
{
    public function run(array $formats)
    {
        foreach ($formats as $supportedFormat => $regRule) {
            if (strpos($dateTimeFormat, $supportedFormat, 0) !== false) {
                $isSupportedFormatFound = true;
                break;
            }
        }

        $regex = array_shift($regRule);
        $mask  = array_shift($regRule);
    }
}