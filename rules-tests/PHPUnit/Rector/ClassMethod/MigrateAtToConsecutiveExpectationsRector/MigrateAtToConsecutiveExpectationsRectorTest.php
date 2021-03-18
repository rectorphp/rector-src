<?php

declare(strict_types=1);

namespace Rector\Tests\PHPUnit\Rector\ClassMethod\MigrateAtToConsecutiveExpectationsRector;

use Iterator;
use Rector\PHPUnit\Rector\ClassMethod\MigrateAtToConsecutiveExpectationsRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class MigrateAtToConsecutiveExpectationsRectorTest extends AbstractRectorTestCase
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
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    protected function getRectorClass(): string
    {
        return MigrateAtToConsecutiveExpectationsRector::class;
    }
}
