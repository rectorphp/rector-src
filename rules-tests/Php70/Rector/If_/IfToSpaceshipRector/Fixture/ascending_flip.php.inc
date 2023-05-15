<?php

namespace Rector\Tests\Php70\Rector\If_\IfToSpaceshipRector\Fixture;

final class AscendingFlip
{
    public function run($languages)
    {
        usort($languages, function ($ascendingFirst, $ascendingSecond) {
            if ($ascendingFirst[0] === $ascendingSecond[0]) {
                return 0;
            } else {
                return ($ascendingSecond[0] < $ascendingFirst[0]) ? 1 : -1;
            }
        });

    }
}
