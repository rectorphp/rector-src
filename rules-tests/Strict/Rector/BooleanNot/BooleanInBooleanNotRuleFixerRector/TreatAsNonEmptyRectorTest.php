<?php

declare(strict_types=1);

namespace Rector\Tests\Strict\Rector\BooleanNot\BooleanInBooleanNotRuleFixerRector;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class TreatAsNonEmptyRectorTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureTreatAsNonEmpty');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/treat_as_non_empty.php';
    }
}
