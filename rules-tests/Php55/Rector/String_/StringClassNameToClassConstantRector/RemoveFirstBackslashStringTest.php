<?php

declare(strict_types=1);

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class RemoveFirstBackslashStringTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/FixtureRemoveFirstBackslashString');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule_remove_first_backslash_string.php';
    }
}
