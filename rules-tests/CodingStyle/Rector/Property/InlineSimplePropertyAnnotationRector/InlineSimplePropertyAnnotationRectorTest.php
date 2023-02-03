<?php

declare(strict_types=1);

namespace Rector\Tests\CodingStyle\Rector\Property\InlineSimplePropertyAnnotationRector;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class InlineSimplePropertyAnnotationRectorTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture/EmptyConfig');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configure_rule_empty_config.php';
    }
}
