<?php

declare(strict_types=1);

namespace Rector\Tests\Configuration;

use PHPUnit\Framework\TestCase;
use Rector\Configuration\PhpLevelSetResolver;
use Rector\Set\ValueObject\SetList;
use Rector\ValueObject\PhpVersion;

final class PhpLevelSetResolverTest extends TestCase
{
    public function test(): void
    {
        $phpSetFiles = PhpLevelSetResolver::resolveFromPhpVersion(PhpVersion::PHP_56);
        $this->assertCount(5, $phpSetFiles);

        $this->assertSame([
            SetList::PHP_52,
            SetList::PHP_53,
            SetList::PHP_54,
            SetList::PHP_55,
            SetList::PHP_56,
        ], $phpSetFiles);
    }
}
