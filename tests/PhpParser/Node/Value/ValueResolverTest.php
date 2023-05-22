<?php

declare(strict_types=1);

namespace Rector\Core\Tests\PhpParser\Node\Value;

use Iterator;
use PhpParser\BuilderFactory;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\Plus;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Core\PhpParser\Node\Value\ValueResolver;
use Rector\Testing\PHPUnit\AbstractTestCase;

final class ValueResolverTest extends AbstractTestCase
{
    private ValueResolver $valueResolver;

    protected function setUp(): void
    {
        $this->boot();
        $this->valueResolver = $this->getService(ValueResolver::class);
    }

    #[DataProvider('dataProvider')]
    public function test(Expr $expr, string | bool | int | float | null $expectedValue): void
    {
        $resolvedValue = $this->valueResolver->getValue($expr);
        $this->assertSame($expectedValue, $resolvedValue);
    }

    /**
     * @return Iterator<array<Expr|mixed>>
     */
    public static function dataProvider(): Iterator
    {
        $builderFactory = new BuilderFactory();

        $classConstFetchNode = $builderFactory->classConstFetch('SomeClass', 'SOME_CONSTANT');
        yield [$classConstFetchNode, 'SomeClass::SOME_CONSTANT'];
        yield [$builderFactory->val(true), true];
        yield [$builderFactory->val(1), 1];
        yield [$builderFactory->val(1.0), 1.0];
        yield [$builderFactory->var('foo'), null];
        yield [new Plus($builderFactory->val(1), $builderFactory->val(1)), 2];
        yield [new Plus($builderFactory->val(1), $builderFactory->var('foo')), null];
    }
}
