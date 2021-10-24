<?php

declare(strict_types=1);

namespace Rector\Naming\PhpArray;

use Rector\Core\Exception\ShouldNotHappenException;

final class ArrayFilter
{
    /**
     * @param mixed[] $values
     * @return string[]
     */
    public function filterWithAtLeastTwoOccurences(array $values): array
    {
        /** @var array<string, int> $valueToCount */
        $valueToCount = array_count_values($values);

        $duplicatedValues = [];

        foreach ($valueToCount as $value => $count) {
            /** @var int $count */
            if ($count < 2) {
                continue;
            }

            if (! is_string($value)) {
                throw new ShouldNotHappenException();
            }

            $duplicatedValues[] = $value;
        }

        return $duplicatedValues;
    }
}
