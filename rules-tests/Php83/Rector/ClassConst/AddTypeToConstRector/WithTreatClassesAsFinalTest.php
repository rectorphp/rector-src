<?php

declare(strict_types=1);

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class WithTreatClassesAsFinalTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/FixtureMarkedAsFinal');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule_marked_classes_as_final.php';
    }
}
