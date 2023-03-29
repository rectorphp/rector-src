<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\Switch_\SwitchTrueToIfRector;

use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class SwitchTrueToIfRectorTest extends AbstractRectorTestCase
{
    #[\PHPUnit\Framework\Attributes\DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): \Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
