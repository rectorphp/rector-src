<?php

declare(strict_types=1);

namespace Rector\Tests\DependencyInjection\Source;

use PHPStan\Php\PhpVersionFactory;

/**
 * Mimics 3rd-party rules (e.g. drupal-rector's FunctionalTestDefaultThemePropertyRector)
 * that inject the PHPStan PhpVersionFactory service via constructor.
 */
final readonly class HasPhpVersionFactoryDependency
{
    public function __construct(
        private PhpVersionFactory $phpVersionFactory
    ) {
    }

    public function getPhpVersionFactory(): PhpVersionFactory
    {
        return $this->phpVersionFactory;
    }
}
