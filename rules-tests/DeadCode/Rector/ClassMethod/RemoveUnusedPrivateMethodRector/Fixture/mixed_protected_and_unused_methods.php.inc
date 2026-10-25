<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector\Fixture;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class MixedProtectedAndUnusedMethods extends TestCase
{
    #[DataProvider('provideValues')]
    public function testValue(int $value): void
    {
        self::assertSame($value, self::class::expectedValue());
        $this->locallyUsed();
    }

    private static function provideValues(): array
    {
        return [[42]];
    }

    private static function expectedValue(): int
    {
        return 42;
    }

    private function unused(): void
    {
    }

    private function locallyUsed(): void
    {
    }
}
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector\Fixture;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class MixedProtectedAndUnusedMethods extends TestCase
{
    #[DataProvider('provideValues')]
    public function testValue(int $value): void
    {
        self::assertSame($value, self::class::expectedValue());
        $this->locallyUsed();
    }

    private static function provideValues(): array
    {
        return [[42]];
    }

    private static function expectedValue(): int
    {
        return 42;
    }

    private function locallyUsed(): void
    {
    }
}
