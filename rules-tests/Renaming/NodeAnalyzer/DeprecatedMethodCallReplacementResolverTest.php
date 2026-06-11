<?php

declare(strict_types=1);

namespace Rector\Tests\Renaming\NodeAnalyzer;

use Iterator;
use PHPStan\Reflection\ReflectionProvider;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Renaming\NodeAnalyzer\DeprecatedMethodCallReplacementResolver;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\Tests\Renaming\NodeAnalyzer\Source\DeprecatedMethodsClient;

final class DeprecatedMethodCallReplacementResolverTest extends AbstractLazyTestCase
{
    private DeprecatedMethodCallReplacementResolver $deprecatedMethodCallReplacementResolver;

    private ReflectionProvider $reflectionProvider;

    protected function setUp(): void
    {
        parent::setUp();

        $this->deprecatedMethodCallReplacementResolver = $this->make(DeprecatedMethodCallReplacementResolver::class);
        $this->reflectionProvider = $this->make(ReflectionProvider::class);
    }

    #[DataProvider('provideData')]
    public function test(string $methodName, ?string $expectedReplacement): void
    {
        $classReflection = $this->reflectionProvider->getClass(DeprecatedMethodsClient::class);
        $extendedMethodReflection = $classReflection->getNativeMethod($methodName);

        $resolvedReplacement = $this->deprecatedMethodCallReplacementResolver->resolve($extendedMethodReflection);
        $this->assertSame($expectedReplacement, $resolvedReplacement);
    }

    /**
     * @return Iterator<string, array{string, (string | null)}>
     */
    public static function provideData(): Iterator
    {
        yield 'use ...() instead' => ['getData', 'fetchData'];
        yield 'replaced by ...()' => ['loadData', 'fetchData'];
        yield '{@see ...()}' => ['readData', 'fetchData'];
        yield 'static use ...() instead' => ['makeOld', 'make'];
        yield 'deprecated without method suggestion' => ['legacyData', null];
        yield 'suggested method does not exist' => ['vanishedData', null];
        yield 'suggested method is itself deprecated' => ['deadEndData', null];
        yield 'not deprecated at all' => ['fetchData', null];
    }
}
