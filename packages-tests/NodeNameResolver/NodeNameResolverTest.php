<?php

declare(strict_types=1);

namespace Rector\Tests\NodeNameResolver;

use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\Testing\PHPUnit\AbstractTestCase;

final class NodeNameResolverTest extends AbstractTestCase
{
    private NodeNameResolver $nodeNameResolver;

    protected function setUp(): void
    {
        $this->boot();
        $this->nodeNameResolver = $this->getService(NodeNameResolver::class);
    }

    public function testGetNameOnMethodCallWithIdentifier(): void
    {
        $methodCallWithNameIdentifier = new MethodCall(
            new Variable('foo'),
            new Identifier('bar')
        );
        $this->assertSame('bar', $this->nodeNameResolver->getName($methodCallWithNameIdentifier->name));
        $this->assertSame('bar', $this->nodeNameResolver->getName($methodCallWithNameIdentifier));
    }
}
