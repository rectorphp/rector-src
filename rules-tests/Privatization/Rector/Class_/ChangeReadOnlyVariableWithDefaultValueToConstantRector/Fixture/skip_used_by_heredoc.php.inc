<?php

namespace Rector\Tests\Privatization\Rector\Class_\ChangeReadOnlyVariableWithDefaultValueToConstantRector\Fixture;

final class SkipUsedByHeredoc
{
    public function run()
    {
        $foo = 'I like pie';

        $bar = <<<EOD
And on this day, it was spoken - "$foo"

EOD;
    }
}
