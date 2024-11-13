<?php

declare(strict_types=1);

namespace Rector\Tests\Composer;

use PHPUnit\Framework\TestCase;
use Rector\Composer\InstalledPackageResolver;
use Rector\Composer\ValueObject\InstalledPackage;

final class InstalledPackageResolverTest extends TestCase
{
    public function test(): void
    {
        $installedPackageResolver = new InstalledPackageResolver(getcwd());
        $installedPackages = $installedPackageResolver->resolve();

        $this->assertContainsOnlyInstancesOf(InstalledPackage::class, $installedPackages);
        $this->assertGreaterThan(80, count($installedPackages));
    }
}
