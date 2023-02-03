<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\Issue7023;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

/**
 * @see https://github.com/rectorphp/rector/issues/7023
 */
final class DoNotPrependAnotherBackslashIfAlreadyPresent73Test extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture/Php73');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule_73.php';
    }
}
