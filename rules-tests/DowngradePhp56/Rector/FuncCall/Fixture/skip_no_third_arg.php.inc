<?php

namespace Rector\Tests\DowngradePhp56\Rector\FuncCall\DowngradeArrayFilterUseConstantRector\Fixture;

final class SkipNoThirdArg
{
    public function run()
    {
        $arr = ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4];

        var_dump(array_filter($arr, function($v) {
            return $v == 4;
        }));
    }
}
