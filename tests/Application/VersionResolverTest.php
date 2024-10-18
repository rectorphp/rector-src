<?php

declare(strict_types=1);

namespace Rector\Tests\Application;

use PHPUnit\Framework\TestCase;
use Rector\Application\VersionResolver;

final class VersionResolverTest extends TestCase
{
    public function test(): void
    {
        $packageVersion = VersionResolver::resolvePackageVersion();

        // latest commit is tagged
        if (str_contains($packageVersion, '.')) {
            $this->assertSame(2, substr_count($packageVersion, '.'));
            return;
        }

        // should be a commit hash size, as we're in untagged test
        $stringLength = strlen($packageVersion);
        $this->assertSame(40, $stringLength);
    }
}
