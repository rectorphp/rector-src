<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use SplFileInfo;

final class RemoveUselessParamTagRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    /**
     * @return Iterator<SplFileInfo>
     */
    public function provideData(): Iterator
    {
        return $this->yieldFilePathsFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
