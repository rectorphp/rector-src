<?php

namespace Rector\Tests\Php85\Rector\FuncCall\OrdSingleByteRector\Fixture;

final class SkipSingleLiteralString
{
    public function run()
    {
        return ord('0');
    }
}
