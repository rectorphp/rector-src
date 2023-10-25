<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Naming\ExpectedNameResolver;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Naming\ExpectedNameResolver\InflectorSingularResolver;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class InflectorSingularResolverTest extends AbstractLazyTestCase
{
    private InflectorSingularResolver $inflectorSingularResolver;

    protected function setUp(): void
    {
        $this->inflectorSingularResolver = $this->make(InflectorSingularResolver::class);
    }

    #[DataProvider('provideData')]
    public function testResolveForForeach(string $currentName, string $expectedSingularName): void
    {
        $singularValue = $this->inflectorSingularResolver->resolve($currentName);
        $this->assertSame($expectedSingularName, $singularValue);
    }

    public static function provideData(): Iterator
    {
        yield ['psr4NamespacesToPaths', 'psr4NamespaceToPath'];
        yield ['nestedNews', 'nestedNew'];
        yield ['news', 'new'];
        yield ['property', 'property'];
        yield ['argsOrOptions', 'argOrOption'];

        // news and plural
        yield ['staticCallsToNews', 'staticCallToNew'];
        yield ['newsToMethodCalls', 'newToMethodCall'];
        yield ['hasFilters', 'hasFilter'];
    }
}
