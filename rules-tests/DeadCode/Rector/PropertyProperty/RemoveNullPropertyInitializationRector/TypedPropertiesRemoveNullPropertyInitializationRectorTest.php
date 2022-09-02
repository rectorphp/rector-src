<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\PropertyProperty\RemoveNullPropertyInitializationRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class TypedPropertiesRemoveNullPropertyInitializationRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureTypedProperties');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/typed_properties.php';
    }
}
