<?php

declare(strict_types=1);

namespace Rector\Tests\Php71\Rector\FuncCall\CountOnNullRector;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class CountOnNullRectorWithPHP73Test extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/FixtureForPhp73');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/is_countable.php';
    }
}
