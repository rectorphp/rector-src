<?php

declare(strict_types=1);

namespace Rector\Order\ValueObject;

final class SortedClassMethodsAndOriginalClassMethods
{
    /**
     * @param array<int, string> $sortedClassMethods
     * @param array<int, string> $originalClassMethods
     */
    public function __construct(
        private array $sortedClassMethods,
        private array $originalClassMethods
    ) {
    }

    /**
     * @return array<int, string>
     */
    public function getSortedClassMethods(): array
    {
        return $this->sortedClassMethods;
    }

    /**
     * @return array<int, string>
     */
    public function getOriginalClassMethods(): array
    {
        return $this->originalClassMethods;
    }

    public function hasOrderChanged(): bool
    {
        return $this->sortedClassMethods !== $this->originalClassMethods;
    }

    public function hasOrderSame(): bool
    {
        $sortedClassMethodValues = array_values($this->sortedClassMethods);
        $originalClassMethodValues = array_values($this->originalClassMethods);

        return $sortedClassMethodValues === $originalClassMethodValues;
    }
}
