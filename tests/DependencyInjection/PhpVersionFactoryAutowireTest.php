<?php

declare(strict_types=1);

namespace Rector\Tests\DependencyInjection;

use PHPStan\Php\PhpVersionFactory;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\Tests\DependencyInjection\Source\HasPhpVersionFactoryDependency;

/**
 * Regression test for a container that cannot autowire the PHPStan PhpVersionFactory,
 * whose constructor has non-class scalar parameters (?int $versionId, ?string $composerPhpVersion).
 *
 * 3rd-party rules like drupal-rector's FunctionalTestDefaultThemePropertyRector inject it directly.
 */
final class PhpVersionFactoryAutowireTest extends AbstractLazyTestCase
{
    public function testResolvedDirectly(): void
    {
        $phpVersionFactory = $this->make(PhpVersionFactory::class);
        $this->assertInstanceOf(PhpVersionFactory::class, $phpVersionFactory);
    }

    public function testResolvedAsConstructorDependency(): void
    {
        $service = $this->make(HasPhpVersionFactoryDependency::class);
        $this->assertInstanceOf(PhpVersionFactory::class, $service->getPhpVersionFactory());
    }
}
