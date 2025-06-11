<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodParameterRector\Fixture;

class SkipNamedArgumentFlipped
{
    public function run(bool $param): void
    {
        $this->isTrue(value: $param, text: 'test');
    }

    private function isTrue(string $text, bool $value): bool
    {
        return $value === true;
    }
}
