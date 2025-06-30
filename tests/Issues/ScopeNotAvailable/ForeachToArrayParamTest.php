<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\ScopeNotAvailable;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Rector\Tests\Issues\ScopeNotAvailable\Variable\ArrayItemForeachValueRector;

final class ForeachToArrayParamTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFileExpectingWarningAboutRuleApplied($filePath, ArrayItemForeachValueRector::class);
    }

    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/FixtureForeachToArrayParam');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/foreach_to_array_param.php';
    }
}
