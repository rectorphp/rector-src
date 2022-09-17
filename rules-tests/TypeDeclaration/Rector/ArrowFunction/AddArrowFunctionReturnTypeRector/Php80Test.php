<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ArrowFunction\AddArrowFunctionReturnTypeRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class Php80Test extends AbstractRectorTestCase
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
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixturePhp80');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/union_types.php';
    }
}
