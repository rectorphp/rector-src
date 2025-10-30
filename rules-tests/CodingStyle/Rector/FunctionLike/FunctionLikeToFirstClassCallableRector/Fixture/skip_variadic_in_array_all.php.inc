<?php

namespace Rector\Tests\CodingStyle\Rector\FunctionLike\FunctionLikeToFirstClassCallableRector\Fixture;

final class SkipVariadicInArrayAll
{
    /**
     * @param array<mixed> $data
     *
     * @phpstan-return ($data is list<array> ? true : false)
     */
    private function isListOfArrays(array $data): bool
    {
        if (!\array_is_list($data)) {
            return false;
        }
        return \array_all($data, static fn(mixed $item): bool => \is_array($item));
    }
}
