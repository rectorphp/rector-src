<?php

declare(strict_types=1);

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class AddToInterfaceMethodsTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/FixtureAddToInterfaceMethods');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/add_to_interface_methods_configured_rule.php';
    }
}
