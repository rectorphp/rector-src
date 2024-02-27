<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\InfiniteLoop;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class DeadStmtUnusedVariableTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/FixtureDeadStmtUnusedVariable');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/dead_stmt_unused_variable.php';
    }
}
