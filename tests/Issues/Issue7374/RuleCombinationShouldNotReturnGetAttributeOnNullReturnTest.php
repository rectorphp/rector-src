<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\Issue7374;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

/**
 * @see https://github.com/rectorphp/rector/issues/7374
 */
final class RuleCombinationShouldNotReturnGetAttributeOnNullReturnTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData()')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    /**
     * @return Iterator<array<string>>
     */
    public static function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
