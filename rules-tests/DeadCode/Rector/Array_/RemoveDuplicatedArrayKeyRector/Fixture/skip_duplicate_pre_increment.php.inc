<?php

namespace Rector\Tests\DeadCode\Rector\Array_\RemoveDuplicatedArrayKeyRector\Fixture;

final class SkipDuplicatePreIncrement
{
    public function run()
    {
        $index = 0;

        [
            $index => 'Max',
            ++$index => 'Max Ϙ',
            ++$index => 'Last',
        ];

        [
            $index => 'Max',
            --$index => 'Max Ϙ',
            --$index => 'Last',
        ];
    }
}