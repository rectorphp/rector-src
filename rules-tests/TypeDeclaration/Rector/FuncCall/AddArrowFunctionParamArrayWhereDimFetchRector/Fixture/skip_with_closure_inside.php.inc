<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\AddArrowFunctionParamArrayWhereDimFetchRector\Fixture;

use stdClass;

final class SkipWithClosureInside
{
    public function run()
    {
        $std = new stdClass();
        $std->name = 'John';

        $array = [['name' => 'John'], $std];

        $result = array_map(fn ($item) =>
            function () use ($item) {
                if ($item instanceof stdClass) {
                    return $item->name;
                }

                return $item['name'];
            }
        , $array);
    }
}
