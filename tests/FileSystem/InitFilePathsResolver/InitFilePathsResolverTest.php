<?php

declare(strict_types=1);

namespace Rector\Core\Tests\FileSystem\InitFilePathsResolver;

use PHPUnit\Framework\TestCase;
use Rector\Core\FileSystem\InitFilePathsResolver;

final class InitFilePathsResolverTest extends TestCase
{
    private InitFilePathsResolver $initFilePathsResolver;

    protected function setUp(): void
    {
        $this->initFilePathsResolver = new InitFilePathsResolver();
    }

    public function test(): void
    {
        $phpDirectoryPaths = $this->initFilePathsResolver->resolve(__DIR__ . '/Fixture/first-project');

        $this->assertSame(['src', 'tests'], $phpDirectoryPaths);
    }
}
