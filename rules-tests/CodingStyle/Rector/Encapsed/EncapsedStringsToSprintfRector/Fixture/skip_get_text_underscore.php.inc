<?php

namespace Rector\Tests\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector\Fixture;

final class SkipGetTextUnderscore
{
    public function run()
    {
        $amount = 3;
        echo _("We have {$amount} apples.");
    }
}