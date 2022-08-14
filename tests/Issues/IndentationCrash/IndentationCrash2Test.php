<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\IndentationCrash;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class IndentationCrash2Test extends AbstractRectorTestCase
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
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture2');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule2.php';
    }
}
