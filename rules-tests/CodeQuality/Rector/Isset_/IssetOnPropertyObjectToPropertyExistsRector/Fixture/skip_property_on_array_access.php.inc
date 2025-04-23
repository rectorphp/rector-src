<?php

namespace Rector\Tests\CodeQuality\Rector\Isset_\IssetOnPropertyObjectToPropertyExistsRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Isset_\IssetOnPropertyObjectToPropertyExistsRector\Source\Collection;
use Rector\Tests\CodeQuality\Rector\Isset_\IssetOnPropertyObjectToPropertyExistsRector\Source\SomeClass;

final class SkipPropertyOnArrayAccess
{
    /**
     * @param  Collection<string, SomeClass>  $values
     */
    private function run(Collection $values): void
    {
        if (! isset($values['_identity']->value)) {
            return;
        }

        echo $values['_identity']->value;
    }
}
