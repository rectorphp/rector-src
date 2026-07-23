<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingClassMethodRector\Source;

abstract class SomeParentCommand
{
    protected function configure(): void
    {
    }

    public function process(int $value): int
    {
        return $value;
    }

    public static function make(): void
    {
    }

    public function anotherMethod(): void
    {
    }
}
