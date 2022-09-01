<?php

declare(strict_types=1);

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class UnionIntersectionTypedPropertyRectorTest extends AbstractRectorTestCase
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
        return $this->yieldFilePathsFromDirectory(__DIR__ . '/FixtureUnionIntersectionTypes');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/union_and_intersection_type_rule.php';
    }
}
