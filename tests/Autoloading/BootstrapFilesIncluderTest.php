<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Autoloading;

use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use Rector\Core\Autoloading\BootstrapFilesIncluder;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class BootstrapFilesIncluderTest extends AbstractLazyTestCase
{
    private BootstrapFilesIncluder $bootstrapFilesIncluder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bootstrapFilesIncluder = $this->make(BootstrapFilesIncluder::class);
    }

    #[DoesNotPerformAssertions]
    public function test(): void
    {
        $this->bootstrapFilesIncluder->includeBootstrapFiles();
    }

    #[DoesNotPerformAssertions]
    public function testPHPStan(): void
    {
        $this->bootstrapFilesIncluder->includePHPStanExtensionsBoostrapFiles();
    }
}
