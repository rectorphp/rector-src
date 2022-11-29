<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class InlinePublicDisableTest extends AbstractRectorTestCase
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
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureInlinePublicDisable');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule_disable_inline_public.php';
    }
}
