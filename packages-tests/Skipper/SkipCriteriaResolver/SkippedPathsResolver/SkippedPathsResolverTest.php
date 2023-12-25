<?php

declare(strict_types=1);

namespace Rector\Tests\Skipper\SkipCriteriaResolver\SkippedPathsResolver;

use Rector\Core\Configuration\Option;
use Rector\Core\Configuration\Parameter\SimpleParameterProvider;
use Rector\Skipper\SkipCriteriaResolver\SkippedPathsResolver;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class SkippedPathsResolverTest extends AbstractLazyTestCase
{
    private SkippedPathsResolver $skippedPathsResolver;

    protected function setUp(): void
    {
        SimpleParameterProvider::setParameter(Option::SKIP, [
            // windows slashes
            __DIR__ . '\non-existing-path',
            __DIR__ . '/Fixture',
            '*\Mask\*',
        ]);

        $this->skippedPathsResolver = $this->make(SkippedPathsResolver::class);
    }

    protected function tearDown(): void
    {
        SimpleParameterProvider::setParameter(Option::SKIP, []);
    }

    public function test(): void
    {
        $skippedPaths = $this->skippedPathsResolver->resolve();

        $this->assertCount(2, $skippedPaths);

        $this->assertSame(__DIR__ . DIRECTORY_SEPARATOR . 'Fixture', $skippedPaths[0]);
        $this->assertSame('*/Mask/*', $skippedPaths[1]);
    }
}
