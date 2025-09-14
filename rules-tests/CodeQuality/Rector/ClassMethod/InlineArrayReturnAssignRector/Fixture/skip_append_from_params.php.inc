<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\InlineArrayReturnAssignRector\Fixture;

final class SkipAppendFromParams
{
    public function run($person)
    {
        $person['name'] = 'Timmy';
        $person['id'] = 1;

        return $person;
    }
}
