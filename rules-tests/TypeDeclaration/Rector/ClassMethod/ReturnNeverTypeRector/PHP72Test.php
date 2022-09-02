<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnNeverTypeRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class PHP72Test extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixturePHP72');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule_php72.php';
    }
}
