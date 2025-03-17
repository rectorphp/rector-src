<?php

declare(strict_types=1);

namespace Rector\Tests\Testing\RectorRuleShouldNotBeApplied;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Exception\Configuration\RectorRuleShouldNotBeAppliedException;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class RectorRuleShouldNotBeAppliedTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->expectException(RectorRuleShouldNotBeAppliedException::class);
        $this->expectExceptionMessage(
            'Failed on fixture file "no_change.php.inc"' . PHP_EOL . PHP_EOL
            . 'File not changed but some Rector rules applied:' . PHP_EOL
            . ' * Rector\Tests\Testing\RectorRuleShouldNotBeApplied\Source\NoChangeRector'
        );

        $this->doTestFile($filePath);
    }

    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
