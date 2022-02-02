<?php

declare(strict_types=1);

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class UnsafeTypedTest extends AbstractRectorTestCase
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
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureUnsafeTyped');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/unsafe_typed_configured_rule.php';
    }
}
