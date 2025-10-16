<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\InlineArrayReturnAssignRector\Fixture;

final class SkipUseClosureUseByRef
{
    public static function getData(): array
    {
        $closure = function () use (&$person)
        {
            $person['name'] = 'Timmy';
            $person['surname'] = 'Back';

            return $person;
        };

        $person = ['test'];
        var_dump($closure());
    }
}
