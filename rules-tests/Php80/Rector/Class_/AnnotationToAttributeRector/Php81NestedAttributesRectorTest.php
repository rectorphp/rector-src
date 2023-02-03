<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

/**
 * @see https://wiki.php.net/rfc/new_in_initializers#nested_attributes
 */
final class Php81NestedAttributesRectorTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/FixturePhp81');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/nested_attributes_php81.php';
    }
}
