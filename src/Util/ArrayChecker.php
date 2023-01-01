<?php

declare(strict_types=1);

namespace Rector\Core\Util;

final class ArrayChecker
{
    /**
     * @param mixed[] $elements
     * @param callable(mixed $element): bool $callable
     */
    public function isExists(array $elements, callable $callable): bool
    {
        foreach ($elements as $element) {
            $isFound = $callable($element);
            if ($isFound) {
                return true;
            }
        }

        return false;
    }
}
