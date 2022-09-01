<?php

declare(strict_types=1);

namespace Rector\Tests\CodingStyle\Rector\Property\InlineSimplePropertyAnnotationRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class InlineSimplePropertyAnnotationWithCustomConfigRectorTest extends AbstractRectorTestCase
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
        return $this->yieldFilePathsFromDirectory(__DIR__ . '/Fixture/CustomConfig');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configure_rule_custom_config.php';
    }
}
