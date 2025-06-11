<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodParameterRector\Fixture;

class SkipUnpackArgument
{
    public function run(bool $param): void
    {
        $this->isTrue(...['test', $param]);
    }

    private function isTrue(string $text, bool $value): bool
    {
        return $value === true;
    }
}
