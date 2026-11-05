<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

final class SkipConditionalReturnBool
{
    /**
     * @param array<mixed> $data
     *
     * @return ($data is list<array> ? true : false)
     */
    private function isListOfArrays(array $data): bool
    {
        return \array_is_list($data) && \array_all($data, static fn (mixed $item): bool => \is_array($item));
    }

    /**
     * @template T of string|int
     * @param T $value
     * @return (T is string ? true : false)
     */
    public function isString(string|int $value): bool
    {
        return is_string($value);
    }
}
