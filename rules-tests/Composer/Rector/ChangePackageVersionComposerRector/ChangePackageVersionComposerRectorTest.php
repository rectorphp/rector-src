<?php

declare(strict_types=1);

namespace Rector\Tests\Composer\Rector\ChangePackageVersionComposerRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Rector\Testing\Fixture\FixtureFileFinder;
use Symplify\SmartFileSystem\SmartFileInfo;

final class ChangePackageVersionComposerRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(SmartFileInfo $fileInfo): void
    {
        $this->doTestFileInfo($fileInfo);
    }

    public function provideData(): Iterator
    {
        return FixtureFileFinder::yieldDirectory(__DIR__ . '/Fixture', '*.json');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/some_config.php';
    }
}
