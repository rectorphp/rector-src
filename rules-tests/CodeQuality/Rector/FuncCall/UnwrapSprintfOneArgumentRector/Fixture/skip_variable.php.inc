<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\UnwrapSprintfOneArgumentRector\Fixture;

class SkipVariable
{
    public function run($format)
    {
        echo sprintf($format);
    }
}
