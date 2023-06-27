<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\InlineArrayReturnAssignRector\Fixture;

final class SkipAssignInTheMiddle
{
    public function run()
    {
        $items = [];

        $result = 'Timmy';
        $items[] = $result . ' Bobby';

        return $items;
    }
}
