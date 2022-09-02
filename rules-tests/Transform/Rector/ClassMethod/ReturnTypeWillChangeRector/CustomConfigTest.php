<?php

declare(strict_types=1);

namespace Rector\Tests\Transform\Rector\ClassMethod\ReturnTypeWillChangeRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class CustomConfigTest extends AbstractRectorTestCase
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
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureCustomConfig');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/custom_config.php';
    }
}
