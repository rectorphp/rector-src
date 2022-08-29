<?php

declare(strict_types=1);

namespace Rector\Tests\PhpDocParser\NodeValue;

use Iterator;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\MagicConst\Dir;
use PhpParser\Node\Scalar\MagicConst\File;
use PhpParser\Node\Scalar\String_;
use Rector\PhpDocParser\NodeValue\NodeValueResolver;
use Rector\Testing\PHPUnit\AbstractTestCase;
use Rector\Tests\PhpDocParser\NodeValue\Fixture\SomeClassWithConstant;

final class NodeValueResolverTest extends AbstractTestCase
{
    private NodeValueResolver $nodeValueResolver;

    protected function setUp(): void
    {
        $this->boot();
        $this->nodeValueResolver = $this->getService(NodeValueResolver::class);
    }

    /**
     * @dataProvider provideData()
     * @param string|class-string<\Rector\Tests\PhpDocParser\NodeValue\NodeValueResolverTest>|bool $expectedValue
     */
    public function test(Expr $expr, string|bool $expectedValue): void
    {
        $resolvedValue = $this->nodeValueResolver->resolve($expr, __FILE__);
        $this->assertSame($expectedValue, $resolvedValue);
    }

    /**
     * @return Iterator<mixed[]|Expr[]>
     */
    public function provideData(): Iterator
    {
        yield [new String_('value'), 'value'];
        yield [new ClassConstFetch(new FullyQualified(self::class), 'class'), self::class];
        yield [new ClassConstFetch(new FullyQualified(SomeClassWithConstant::class), 'NAME'), 'value'];
        yield [new Dir(), __DIR__];
        yield [new ConstFetch(new Name('true')), true];
        yield [new Concat(new Dir(), new String_('/example.latte')), __DIR__ . '/example.latte'];
        $args = [new Arg(new String_('.php')), new Arg(new String_('.latte')), new Arg(new File())];
        yield [new FuncCall(new Name('str_replace'), $args), __DIR__ . '/NodeValueResolverTest.latte'];
    }
}
