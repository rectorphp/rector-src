<?php

declare(strict_types=1);

namespace Rector\Tests\Transform\Rector\ClassMethod\ReturnTypeWillChangeRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class CustomConfigTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(SmartFileInfo $fileInfo): void
    {
        $this->doTestFileInfo($fileInfo);
    }

    /**
     * @return Iterator<SmartFileInfo>
     */
    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureCustomConfig');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/custom_config.php';
    }
}
