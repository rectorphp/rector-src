<?php

declare(strict_types=1);

namespace Rector\Tests\Configuration;

use PHPUnit\Framework\TestCase;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;

final class RectorConfigBuilderTest extends TestCase
{
    /**
     * @var string[]
     */
    private array $deprecations = [];

    protected function setUp(): void
    {
        $this->deprecations = [];
        set_error_handler(function (int $errorNumber, string $errorMessage): bool {
            $this->deprecations[] = $errorMessage;
            return true;
        }, E_USER_DEPRECATED);
    }

    protected function tearDown(): void
    {
        restore_error_handler();
    }

    public function testWithSetsWarnsOnDeprecatedPhpSet(): void
    {
        RectorConfig::configure()
            ->withSets([SetList::PHP_82]);

        $this->assertCount(1, $this->deprecations);
        $this->assertStringContainsString('withPhpSets()', $this->deprecations[0]);
    }

    public function testWithSetsDoesNotWarnOnNonPhpSet(): void
    {
        RectorConfig::configure()
            ->withSets([SetList::CODE_QUALITY]);

        $this->assertSame([], $this->deprecations);
    }
}
