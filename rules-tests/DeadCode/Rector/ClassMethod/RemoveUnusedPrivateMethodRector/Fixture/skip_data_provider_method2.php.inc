<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector\Fixture;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class SkipDataProviderMethod2 extends TestCase
{
    #[DataProvider('fooDataProvider')]
    public function testFoo(): void
    {
    }

    private function fooDataProvider(): array
    {
        return [
            [],
        ];
    }
}
