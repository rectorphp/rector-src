<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Class_\DoctrineAnnotationClassToAttributeRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class ShouldNotRemoveAnnotationDirectConfigRectorTest extends AbstractRectorTestCase
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
        return $this->yieldFilePathsFromDirectory(__DIR__ . '/FixtureShouldNotRemoveAnnotationDirectConfig');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configure_direct_shouldnot_remove_annotation.php';
    }
}
