<?php

declare(strict_types=1);

namespace Rector\Tests\Configuration;

use PHPUnit\Framework\Attributes\RunClassInSeparateProcess;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector;

#[RunClassInSeparateProcess]
final class RectorConfigBuilderTest extends AbstractLazyTestCase
{
    public function testWithImport(): void
    {
        $rectorConfig = self::getContainer();

        $rectorConfig->configure()
            ->withImport(__DIR__ . '/config/imported_config.php')($rectorConfig);

        $this->assertTrue($rectorConfig->has(ReturnTypeFromReturnNewRector::class));
    }

    public function testWithMultipleImports(): void
    {
        $rectorConfig = self::getContainer();

        $rectorConfig->configure()
            ->withImport(
                __DIR__ . '/config/imported_config.php',
                __DIR__ . '/config/second_imported_config.php'
            )($rectorConfig);

        $this->assertTrue($rectorConfig->has(ReturnTypeFromReturnNewRector::class));
        $this->assertTrue($rectorConfig->has(RemoveUnusedPrivateMethodRector::class));
    }

    public function testWithNestedImport(): void
    {
        $rectorConfig = self::getContainer();

        $rectorConfig->configure()
            ->withImport(__DIR__ . '/config/nested_import_config.php')($rectorConfig);

        $this->assertTrue($rectorConfig->has(ReturnTypeFromReturnNewRector::class));
    }
}
