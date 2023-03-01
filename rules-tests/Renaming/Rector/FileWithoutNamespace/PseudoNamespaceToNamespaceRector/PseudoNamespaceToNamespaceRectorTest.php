<?php

declare(strict_types=1);

namespace Rector\Tests\Renaming\Rector\FileWithoutNamespace\PseudoNamespaceToNamespaceRector;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

#[RunTestsInSeparateProcesses]
final class PseudoNamespaceToNamespaceRectorTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
