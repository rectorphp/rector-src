<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

/**
 * @deprecated This rule is surpassed by more advanced one
 * Use @see FirstClassCallableRector instead
 */
final class CallableThisArrayToAnonymousFunctionRectorTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
