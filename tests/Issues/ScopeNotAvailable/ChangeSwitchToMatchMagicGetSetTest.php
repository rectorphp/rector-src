<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\ScopeNotAvailable;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class ChangeSwitchToMatchMagicGetSetTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    /**
     * @return Iterator<array<string>>
     */
    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureChangeSwitchToMatchMagicGetSet');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/change_switch_to_match_magic_get_set_configurable_rule.php';
    }
}
