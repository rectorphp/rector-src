<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnAnnotationIncorrectNullableRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class ReturnAnnotationIncorrectNullableRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    /**
     * @return Iterator<array<string>>
     */
    public function provideData(): Iterator
    {
        return $this->yieldFilePathsFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/config.php';
    }
}
