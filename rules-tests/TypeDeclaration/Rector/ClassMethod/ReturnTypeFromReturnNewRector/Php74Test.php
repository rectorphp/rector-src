<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class Php74Test extends AbstractRectorTestCase
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
        return $this->yieldFilePathsFromDirectory(__DIR__ . '/FixturePhp74');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/php74.php';
    }
}
