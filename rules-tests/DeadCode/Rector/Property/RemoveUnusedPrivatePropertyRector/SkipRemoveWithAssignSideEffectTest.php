<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class SkipRemoveWithAssignSideEffectTest extends AbstractRectorTestCase
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
        return $this->yieldFilePathsFromDirectory(__DIR__ . '/FixtureSkipSideEffect');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule_skip_side_effect.php';
    }
}
