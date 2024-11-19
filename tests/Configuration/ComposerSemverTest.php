<?php

declare(strict_types=1);

namespace Rector\Tests\Configuration;

use PHPUnit\Framework\TestCase;
use Rector\Configuration\ComposerSemver;

final class ComposerSemverTest extends TestCase
{
    public function testInstalled(): void
    {
        $isPHPUnit10 = ComposerSemver::matchesPackageVersion('phpunit/phpunit', '10.5');
        $this->assertTrue($isPHPUnit10);
    }

    public function testNoInstalled(): void
    {
        $isPHPUnit9 = ComposerSemver::matchesPackageVersion('phpunit/phpunit', '9.3');
        $this->assertFalse($isPHPUnit9);

        $isPHPUnit11 = ComposerSemver::matchesPackageVersion('phpunit/phpunit', '11.5');
        $this->assertFalse($isPHPUnit11);
    }
}
