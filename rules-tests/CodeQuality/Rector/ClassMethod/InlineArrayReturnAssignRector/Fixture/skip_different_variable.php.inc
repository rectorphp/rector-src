<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\InlineArrayReturnAssignRector\Fixture;

final class SkipDifferentVariable
{
    public function create()
    {
        $item['sub_key'] = 500;

        $items['name'] = 'John';
        $items['surname'] = 'Doe';
        $items['age'] = $item;

        return $items;
    }
}
