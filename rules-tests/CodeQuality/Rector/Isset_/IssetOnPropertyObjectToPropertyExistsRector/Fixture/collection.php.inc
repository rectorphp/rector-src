<?php

namespace Rector\Tests\CodeQuality\Rector\Isset_\IssetOnPropertyObjectToPropertyExistsRector\Fixture;

/**
 * @template TKey of array-key
 *
 * @template-covariant TValue
 *
 * @implements \ArrayAccess<TKey, TValue>
 */
class Collection implements ArrayAccess, CanBeEscapedWhenCastToString, Enumerable {}

class VariableValue {
    public function __construct(public string $name, public mixed $value, public ?string $expression, public ?AnswerType $type) {}
}

final class DemoFile
{
    /**
     * @param  Collection<string, VariableValue>  $values
     */
    private function setBlockTitle(Collection $values): void
    {
        if (! isset($values['_identity']->value)) {
            return;
        }

        echo $values['_identity']->value;
    }
}
?>
