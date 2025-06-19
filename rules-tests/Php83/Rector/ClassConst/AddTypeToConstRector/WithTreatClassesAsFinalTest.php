<?php

declare(strict_types=1);

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Configuration\Parameter\FeatureFlags;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class WithTreatClassesAsFinalTest extends AbstractRectorTestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();

        // reset feature flags to avoid side effects in other rules
        FeatureFlags::reset();
    }

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
        return __DIR__ . '/config/marked_classes_as_final.php';
    }
}
