<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class Php80RectorTest extends AbstractRectorTestCase
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
        return $this->yieldFilePathsFromDirectory(__DIR__ . '/FixtureForPhp80');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/php_80.php';
    }
}
