<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\InfiniteLoop;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class DeclareStrictTypesPhp83SetTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/FixtureDeclareStrictTypesParseStr');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/declare_strict_types_parsestr.php';
    }
}
