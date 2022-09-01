<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class TypedPropertyFromAssignsComplexTypesRectorTest extends AbstractRectorTestCase
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
        return $this->yieldFilePathsFromDirectory(__DIR__ . '/FixtureComplexTypes');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/complex_types.php';
    }
}
