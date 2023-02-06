<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\PartialValueDocblockRemoval;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

#[RunTestsInSeparateProcesses]
final class PartialValueDocblockRemovalTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
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
