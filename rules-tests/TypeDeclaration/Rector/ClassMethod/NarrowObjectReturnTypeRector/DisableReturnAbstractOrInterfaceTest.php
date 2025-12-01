<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class DisableReturnAbstractOrInterfaceTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/FixtureSkipReturnAbstractOrInterface');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/disable_return_abstract_or_interface_configured_rule.php';
    }
}
