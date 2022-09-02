<?php

declare(strict_types=1);

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\MakeInheritedMethodVisibilitySameAsParentRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class SkipParentConstructOverrideInPHP72Test extends AbstractRectorTestCase
{
    /**
     * @see https://phpunit.readthedocs.io/en/8.3/incomplete-and-skipped-tests.html#incomplete-and-skipped-tests-requires-tables-api
     *
     * @dataProvider provideData()
     */
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureForPhp72');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/php_72.php';
    }
}
