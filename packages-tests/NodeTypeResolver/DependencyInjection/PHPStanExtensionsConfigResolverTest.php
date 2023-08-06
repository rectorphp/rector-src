<?php

declare(strict_types=1);

namespace Rector\Tests\NodeTypeResolver\DependencyInjection;

use Rector\NodeTypeResolver\DependencyInjection\PHPStanExtensionsConfigResolver;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class PHPStanExtensionsConfigResolverTest extends AbstractLazyTestCase
{
    public function test(): void
    {
        $phpStanExtensionsConfigResolver = $this->make(PHPStanExtensionsConfigResolver::class);

        // these configs are required by this package, so must be in there

        $phpunitExtensionFilePath = realpath(__DIR__ . '/../../../vendor/phpstan/phpstan-phpunit/extension.neon');

        $assertExtensionFilePath = realpath(
            __DIR__ . '/../../../vendor/phpstan/phpstan-webmozart-assert/extension.neon'
        );

        $extensionConfigFiles = $phpStanExtensionsConfigResolver->resolve();

        $this->assertContains($phpunitExtensionFilePath, $extensionConfigFiles);
        $this->assertContains($assertExtensionFilePath, $extensionConfigFiles);
    }
}
