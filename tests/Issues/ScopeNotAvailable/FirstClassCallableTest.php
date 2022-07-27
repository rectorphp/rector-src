<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\ScopeNotAvailable;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class FirstClassCallableTest extends AbstractRectorTestCase
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
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureFirstClassCallable');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/first_class_callable_configured_rule.php';
    }
}
