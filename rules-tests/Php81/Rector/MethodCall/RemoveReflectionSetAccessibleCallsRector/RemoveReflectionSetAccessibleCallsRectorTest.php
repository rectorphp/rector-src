<?php

declare(strict_types=1);

namespace Rector\Tests\Php81\Rector\MethodCall\RemoveReflectionSetAccessibleCallsRector;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class RemoveReflectionSetAccessibleCallsRectorTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function testRule(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    /**
     * @return Iterator<array<string>>
     */
    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
