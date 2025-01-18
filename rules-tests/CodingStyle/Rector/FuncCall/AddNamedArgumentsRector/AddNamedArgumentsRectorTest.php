<?php

declare(strict_types=1);

namespace Rector\Tests\CodingStyle\Rector\FuncCall\AddNamedArgumentsRector;

use Rector\CodingStyle\Rector\FuncCall\AddNamedArgumentsRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class AddNamedArgumentsRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideCases()
     */
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideCases(): iterable
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/rector.php';
    }

    protected function getRectorClass(): string
    {
        return AddNamedArgumentsRector::class;
    }
}
