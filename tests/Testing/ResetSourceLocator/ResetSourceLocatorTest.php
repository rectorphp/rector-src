<?php

declare(strict_types=1);

namespace Rector\Tests\Testing\ResetSourceLocator;

use PHPUnit\Framework\Attributes\Depends;
use Rector\NodeTypeResolver\Reflection\BetterReflection\SourceLocatorProvider\DynamicSourceLocatorProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class ResetSourceLocatorTest extends AbstractRectorTestCase
{
    public function testProcessFixture(): void
    {
        $this->doTestFile(__DIR__ . '/Fixture/some_function.php.inc');
    }

    #[Depends('testProcessFixture')]
    public function testNextTestDoesNotLocateDeletedInputFile(): void
    {
        $dynamicSourceLocatorProvider = $this->make(DynamicSourceLocatorProvider::class);

        $this->assertTrue($dynamicSourceLocatorProvider->arePathsEmpty());
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
