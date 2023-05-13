<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\RemoveSoleValueSprintfRector\Fixture;

final class SkipNonString
{
    public function run()
    {
        $value = sprintf('%s', 1000);
    }
}
