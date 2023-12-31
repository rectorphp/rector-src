<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\ScopeNotAvailable;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class JsonThrowCaseSensitiveConstFetchTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/FixtureJsonThrowCaseSensitiveConstFetch');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/json_throw_case_sensitive_const_fetch.php';
    }
}
