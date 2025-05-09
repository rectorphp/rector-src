<?php

declare(strict_types=1);

namespace Rector\Renaming\Collector;

final class RenamedEnumCaseCollector
{
    /**
     * @var string[]
     */
    private array $names = [];

    public function add(string $name): void
    {
        $this->names[] = $name;
    }

    public function has(string $name): bool
    {
        return in_array($name, $this->names, true);
    }
}
