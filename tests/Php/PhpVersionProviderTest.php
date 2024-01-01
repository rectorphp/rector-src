<?php

declare(strict_types=1);

namespace Rector\Tests\Php;

use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Php\PhpVersionProvider;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class PhpVersionProviderTest extends AbstractLazyTestCase
{
    public function test(): void
    {
        SimpleParameterProvider::setParameter(Option::PHP_VERSION_FEATURES, 100000);

        $phpVersionProvider = $this->make(PhpVersionProvider::class);
        $phpVersion = $phpVersionProvider->provide();

        $this->assertSame(100000, $phpVersion);
    }
}
