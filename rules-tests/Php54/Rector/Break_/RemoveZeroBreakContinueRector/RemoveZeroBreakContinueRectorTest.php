<?php

declare(strict_types=1);

namespace Rector\Tests\Php54\Rector\Break_\RemoveZeroBreakContinueRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class RemoveZeroBreakContinueRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(string $filepath): void
    {
        // to prevent loading PHP 5.4+ invalid code
        $this->doTestFile($filepath);
    }

    public function provideData(): Iterator
    {
        return $this->yieldFilePathsFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
