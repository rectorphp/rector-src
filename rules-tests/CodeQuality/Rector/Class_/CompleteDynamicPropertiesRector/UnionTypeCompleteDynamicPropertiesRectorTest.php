<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class UnionTypeCompleteDynamicPropertiesRectorTest extends AbstractRectorTestCase
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
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureUnionTypes');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
