<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\InlineArrayReturnAssignRector\Fixture;

final class SkipMultipleReturns
{
    public function create()
    {
        $items['name'] = 'John';
        $items['surname'] = 'Doe';
        $items['age'] = 50;

        if (mt_rand(1, 2)) {
            return [];
        }

        return $items;
    }
}
