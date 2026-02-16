<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\Issue9646;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class Issue9646WithoutEagerTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/FixtureWithoutEager');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule_without_eager.php';
    }
}
