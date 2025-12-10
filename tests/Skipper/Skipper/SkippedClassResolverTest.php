<?php

declare(strict_types=1);

namespace Rector\Tests\Skipper\Skipper;

use Rector\Skipper\SkipCriteriaResolver\SkippedClassResolver;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class SkippedClassResolverTest extends AbstractLazyTestCase
{
    public function test(): void
    {
        $skippedClassResolver = $this->make(SkippedClassResolver::class);

        $this->assertSame([], $skippedClassResolver->resolveDeprecatedSkippedClasses());
    }
}
