<?php

declare(strict_types=1);

namespace Rector\Tests\Php71\Rector\FuncCall\CountOnNullRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class CountOnNullRectorWithPHP73Test extends AbstractRectorTestCase
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
        return $this->yieldFilePathsFromDirectory(__DIR__ . '/FixtureForPhp73');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/is_countable.php';
    }
}
