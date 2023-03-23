<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector\Fixture;

final class SkipNoDoc
{
    public function run()
    {
        $stdClass = $this->getStdClass();
        if ($stdClass === null) {
            return;
        }
    }
}
