<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictGetterMethodReturnTypeRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class TypedPropertyFromStrictGetterMethodReturnTypePhp73RectorTest extends AbstractRectorTestCase
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
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixturePhp73');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/rule_php73.php';
    }
}
