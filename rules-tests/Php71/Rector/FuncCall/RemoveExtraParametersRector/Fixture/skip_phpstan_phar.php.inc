<?php

namespace Rector\Tests\Php71\Rector\FuncCall\RemoveExtraParametersRector\Fixture;

use Rector\Tests\Php71\Rector\FuncCall\RemoveExtraParametersRector\Source\Stan;

final class SkipPHPStanPhar
{
    public function run()
    {
        $stan = new Stan();
        $stan->foo(1, 2);

        Stan::bar(1, 2);
    }
}
