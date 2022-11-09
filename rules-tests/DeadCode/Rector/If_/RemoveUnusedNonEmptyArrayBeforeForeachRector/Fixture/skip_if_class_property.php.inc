<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveUnusedNonEmptyArrayBeforeForeachRector\Fixture;

class SkipIfClassProperty
{
    /** @var string[] */
    private array $allowedValues = [];

    protected function filterValues(array $values): array
    {
        if ($this->allowedValues) {
            foreach ($values as $index => $value) {
                if (!isset($this->allowedValues[$value])) {
                    unset($values[$index]);
                }
            }
        }

        return $values;
    }
}
