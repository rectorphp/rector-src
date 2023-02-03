<?php

declare(strict_types=1);

namespace Rector\Tests\Php70\Rector\Ternary\TernaryToNullCoalescingRector;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

/**
 * Some tests copied from:
 * https://github.com/FriendsOfPHP/PHP-CS-Fixer/commit/0db4f91088a3888a7c8b26e5a36fba53c0d9507c#diff-02f477b178d0dc5b25ac05ab3b59e7c7
 * https://github.com/slevomat/coding-standard/blob/master/tests/Sniffs/ControlStructures/data/requireNullCoalesceOperatorErrors.fixed.php
 */
final class TernaryToNullCoalescingRectorTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    /**
     * @return Iterator<array<string>>
     */
    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
