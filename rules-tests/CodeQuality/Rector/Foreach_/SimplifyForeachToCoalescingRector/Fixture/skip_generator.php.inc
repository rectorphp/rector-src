<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\SimplifyForeachToCoalescingRector\Fixture;

final class SkipGenerator
{
    public function load(): \Generator
    {
        foreach (['a' => 1, 'b' => 2] as $key => $value) {
            yield $key => $value;
        }
    }

    public function loadByKey(string $name): ?int
    {
        foreach ($this->load() as $key => $value) {
            if ($key === $name) {
                return $value;
            }
        }
        return null;
    }
}