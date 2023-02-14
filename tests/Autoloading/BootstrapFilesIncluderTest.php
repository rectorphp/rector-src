<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Autoloading;

use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use Rector\Core\Autoloading\BootstrapFilesIncluder;
use Rector\Testing\PHPUnit\AbstractTestCase;

final class BootstrapFilesIncluderTest extends AbstractTestCase
{
    private BootstrapFilesIncluder $bootstrapFilesIncluder;

    protected function setUp(): void
    {
        $this->boot();

        $this->bootstrapFilesIncluder = $this->getService(BootstrapFilesIncluder::class);
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
