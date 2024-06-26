<?php

namespace Rector\Tests\CodeQuality\Rector\FunctionLike\SimplifyUselessVariableRector\Fixture;

class SkipMultiConcatString
{
    public function run()
    {
        $content = 'Hello, ';
        $content .= 'World!';

        return $content;
    }
}
