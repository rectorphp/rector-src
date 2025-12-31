<?php

declare(strict_types=1);

namespace Rector\Tests\Instanceof_\Rector\BooleanNot\ParenthesizeNegatedInstanceofRector;

use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class AddParenthesesTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): \Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/FixtureAdd');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/add_parentheses.php';
    }
}
