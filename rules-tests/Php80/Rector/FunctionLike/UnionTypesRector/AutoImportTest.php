<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\FunctionLike\UnionTypesRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class AutoImportTest extends AbstractRectorTestCase
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
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureAutoImport');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule_auto_import.php';
    }
}
