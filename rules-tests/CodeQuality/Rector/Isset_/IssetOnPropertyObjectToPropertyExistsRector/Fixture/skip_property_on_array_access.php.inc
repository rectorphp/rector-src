<?php

namespace Rector\Tests\CodeQuality\Rector\Isset_\IssetOnPropertyObjectToPropertyExistsRector\Fixture;

use ArrayAccess;
use Enumerable;

/**
 * @template TKey of array-key
 *
 * @template-covariant TValue
 *
 * @implements ArrayAccess<TKey, TValue>
 */
class Collection implements ArrayAccess, Enumerable {}

class VariableValue {
    public function __construct(public mixed $value) {}
}

final class SkipPropertyOnArrayAccess
{
    /**
     * @param  Collection<string, VariableValue>  $values
     */
    private function run(Collection $values): void
    {
        if (! isset($values['_identity']->value)) {
            return;
        }

        echo $values['_identity']->value;
    }
}
