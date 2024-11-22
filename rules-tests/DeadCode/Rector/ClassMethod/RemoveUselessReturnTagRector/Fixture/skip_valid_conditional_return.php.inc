<?php

declare(strict_types = 1);

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

final readonly class SkipValidConditionalReturn
{
    /**
     * Groups an array by a key returned by a callback.
     *
     * Example:
     *
     * return SkipValidConditionalReturn::groupBy(
     *     $results,
     *     fn ($ticket) => (string) $ticket->getFile()->getId(),
     * );
     *
     * @template TValue of mixed
     * @template TCallbackValue of mixed
     * @template TKey of array-key
     * @param array<TValue> $input
     * @param callable(TValue): TKey $keyCallback
     * @param null|callable(TValue): TCallbackValue $valueCallback
     *
     * @return ($valueCallback is null ? array<TKey, non-empty-list<TValue>> : array<TKey, non-empty-list<TCallbackValue>>)
     */
    public static function groupBy(array $input, callable $keyCallback, ?callable $valueCallback = null) : array
    {
        $output = [];
        foreach ($input as $result) {
            $output[$keyCallback($result)][] = $valueCallback !== null ? $valueCallback($result) : $result;
        }

        return $output;
    }
}