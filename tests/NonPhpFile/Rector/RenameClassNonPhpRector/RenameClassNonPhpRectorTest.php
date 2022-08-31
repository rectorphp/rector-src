<?php

declare(strict_types=1);

namespace Rector\Core\Tests\NonPhpFile\Rector\RenameClassNonPhpRector;

use Iterator;
use Rector\Testing\Fixture\FixtureFileFinder;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class RenameClassNonPhpRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(SmartFileInfo $fixtureFileInfo): void
    {
        $this->doTestFileInfo($fixtureFileInfo);
    }

    /**
     * @return Iterator<array<int, SmartFileInfo>>
     */
    public function provideData(): Iterator
    {
        return FixtureFileFinder::yieldDirectory(__DIR__ . '/Fixture', '*');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
