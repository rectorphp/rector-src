<?php

declare(strict_types=1);

namespace Rector\Tests\Naming\Rector\Assign\RenameVariableToMatchMethodCallReturnTypeRector\Source;

final class SortableBuilder
{
    public static function for(string $class): self
    {
        return new self();
    }

    public function allowedSorts(callable $callback): self
    {
        return $this;
    }

    public function defaultSort(string $sort): self
    {
        return $this;
    }

    public function jsonPaginate(): array
    {
        return [];
    }
}
