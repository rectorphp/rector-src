<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\RepeatedTwice;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

/**
 * The return-type rule depends on the output of the param-type rule registered after it,
 * so the file only reaches its final state on the 2nd pass
 *
 * @see https://github.com/rectorphp/rector-src/pull/8826
 */
final class RepeatedTwiceTest extends AbstractRectorTestCase
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
