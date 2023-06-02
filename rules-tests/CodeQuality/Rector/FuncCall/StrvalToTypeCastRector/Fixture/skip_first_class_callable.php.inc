<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\StrvalToTypeCastRector\Fixture;

final class SkipFirstClassCallable
{
    public function run()
    {
        $arr = array_map(
            strval(...),
            [1, 2, 3]
        );

        return $arr;
    }
}
