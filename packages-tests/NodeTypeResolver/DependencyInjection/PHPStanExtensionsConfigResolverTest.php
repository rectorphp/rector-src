<?php

declare(strict_types=1);

namespace Rector\Tests\NodeTypeResolver\DependencyInjection;

use Rector\NodeTypeResolver\DependencyInjection\PHPStanExtensionsConfigResolver;
use Rector\Testing\PHPUnit\AbstractTestCase;

final class PHPStanExtensionsConfigResolverTest extends AbstractTestCase
{
    private PHPStanExtensionsConfigResolver $phpStanExtensionsConfigResolver;

    protected function setUp(): void
    {
        $this->boot();

        $this->phpStanExtensionsConfigResolver = $this->getService(PHPStanExtensionsConfigResolver::class);
    }

    public function test(): void
    {
        // these configs are required by this package, so must be in there

        $phpunitExtensionFilePath = realpath(__DIR__ . '/../../../vendor/phpstan/phpstan-phpunit/extension.neon');

        $assertExtensionFilePath = realpath(
            __DIR__ . '/../../../vendor/phpstan/phpstan-webmozart-assert/extension.neon'
        );

        $extensionConfigFiles = $this->phpStanExtensionsConfigResolver->resolve();

        $this->assertContains($phpunitExtensionFilePath, $extensionConfigFiles);
        $this->assertContains($assertExtensionFilePath, $extensionConfigFiles);
    }
}
