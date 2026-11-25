<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

final class SkipUntypedSubjectWithStringCases
{
    public function run($value): string
    {
        switch ($value) {
            case 'a':
                return 'first';
            case '1':
                return 'second';
            default:
                return 'other';
        }
    }
}
