<?php

declare(strict_types=1);

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class AllowHasChildRectorTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/FixtureAllowHasChild');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/allow_has_child.php';
    }
}
