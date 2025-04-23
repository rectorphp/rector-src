<?php
/**
 * @template TKey of array-key
 *
 * @template-covariant TValue
 *
 * @implements \ArrayAccess<TKey, TValue>
 */
namespace Rector\Tests\CodeQuality\Rector\Isset_\IssetOnPropertyObjectToPropertyExistsRector\Fixture;

class Collection implements ArrayAccess, CanBeEscapedWhenCastToString, Enumerable {}
namespace Rector\Tests\CodeQuality\Rector\Isset_\IssetOnPropertyObjectToPropertyExistsRector\Fixture;

class VariableValue {
    public function __construct(public string $name, public mixed $value, public ?string $expression, public ?AnswerType $type) {}
}

namespace Rector\Tests\CodeQuality\Rector\Isset_\IssetOnPropertyObjectToPropertyExistsRector\Fixture;

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
