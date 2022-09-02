<?php

declare(strict_types=1);

namespace Rector\Tests\CodingStyle\Rector\ClassConst\VarConstantCommentRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class VarConstantCommentRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    /**
     * @return Iterator<array<string>>
     */
    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
