<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictIntPregSlitFuncCallLimitArgRector\Fixture;

class SkipNoLimit
{
    public function run($output)
    {
        $keywords = preg_split("/[\s,]+/", $output);
    }
}