<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\UnwrapSprintfOneArgumentRector\Fixture;

class SkipPercentLiteral
{
    public function run()
    {
        echo sprintf('100%%');
    }
}
