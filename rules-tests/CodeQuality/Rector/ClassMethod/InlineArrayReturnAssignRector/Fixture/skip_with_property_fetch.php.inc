<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\InlineArrayReturnAssignRector\Fixture;

final class SkipWithPropertyFetch
{
    public function run($person2)
    {
        $person = [];

        $person['name'] = 'Timmy';
        $this->person['name'] = 'Back';

        return $person;
    }
}
