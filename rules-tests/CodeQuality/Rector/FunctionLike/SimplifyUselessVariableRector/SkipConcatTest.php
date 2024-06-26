<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\FunctionLike\SimplifyUselessVariableRector;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class SkipConcatTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/FixtureSkipConcat');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/skip_concat.php';
    }
}
