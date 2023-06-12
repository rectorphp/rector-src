<?php

declare(strict_types=1);

namespace Rector\Tests\Skipper\SkipCriteriaResolver\SkippedPathsResolver;

use PHPUnit\Framework\TestCase;
use Rector\Core\Kernel\RectorKernel;
use Rector\Skipper\SkipCriteriaResolver\SkippedPathsResolver;

final class SkippedPathsResolverTest extends TestCase
{
    private SkippedPathsResolver $skippedPathsResolver;

    protected function setUp(): void
    {
        $rectorKernel = new RectorKernel();
        $containerBuilder = $rectorKernel->createFromConfigs([__DIR__ . '/config/config.php']);

        $this->skippedPathsResolver = $containerBuilder->get(SkippedPathsResolver::class);
    }

    public function test(): void
    {
        $skippedPaths = $this->skippedPathsResolver->resolve();
        $this->assertCount(2, $skippedPaths);

        $this->assertSame('*/Mask/*', $skippedPaths[1]);
    }
}
