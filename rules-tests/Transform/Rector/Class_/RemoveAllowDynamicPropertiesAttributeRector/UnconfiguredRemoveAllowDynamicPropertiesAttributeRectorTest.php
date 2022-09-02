<?php

declare(strict_types=1);

namespace Rector\Tests\Transform\Rector\Class_\RemoveAllowDynamicPropertiesAttributeRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class UnconfiguredRemoveAllowDynamicPropertiesAttributeRectorTest extends AbstractRectorTestCase
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
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureAllClasses');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/unconfigured_rule.php';
    }
}
