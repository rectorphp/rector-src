<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class AutoImportedAnnotationToAttributeRectorTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData()')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureAutoImported');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/auto_import.php';
    }
}
