<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\AutoImportShortName;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class DisableShortClassNameAutoImportTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData()')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    /**
     * @return Iterator<array<string>>
     */
    public static function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureDisableShortClassNameAutoImport');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/disable_short_name_configured_rule.php';
    }
}
