<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\ScopeNotAvailable;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class FirstClassCallableTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/FixtureFirstClassCallable');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/first_class_callable_configured_rule.php';
    }
}
