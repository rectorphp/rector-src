<?php

namespace Rector\Tests\Php85\Rector\StmtsAwareInterface\NestedToPipeOperatorRector\Fixture;

final class SkipNestedFunctions
{
    public function run()
    {
        $result = trim(strtoupper("Hello World"));
    }
}
