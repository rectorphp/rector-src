<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector\Fixture;

use PHPUnit\Framework\TestCase;

class SkipDataProviderMethod extends TestCase
{
    /**
     * @dataProvider fooDataProvider
     */
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
