<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Class_\DoctrineAnnotationClassToAttributeRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class ShouldNotRemoveAnnotationRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureShouldNotRemoveAnnotation');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/should_not_remove_annotation.php';
    }
}
