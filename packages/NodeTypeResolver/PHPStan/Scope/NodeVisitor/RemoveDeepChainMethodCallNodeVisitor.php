<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PHPStan\Scope\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Nop;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use Rector\Core\Configuration\Option;
use Rector\Core\Configuration\Parameter\SimpleParameterProvider;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\NodeTypeResolver\PHPStan\Scope\Contract\NodeVisitor\ScopeResolverNodeVisitorInterface;

/**
 * Skips performance trap in PHPStan: https://github.com/phpstan/phpstan/issues/254
 */
final class RemoveDeepChainMethodCallNodeVisitor extends NodeVisitorAbstract implements ScopeResolverNodeVisitorInterface
{
    private readonly int $nestedChainMethodCallLimit;

    private ?Expression $removingExpression = null;

    public function __construct(
        private readonly BetterNodeFinder $betterNodeFinder,
    ) {
        $this->nestedChainMethodCallLimit = SimpleParameterProvider::provideIntParameter(
            Option::NESTED_CHAIN_METHOD_CALL_LIMIT
        );
    }

    public function enterNode(Node $node): ?int
    {
        if (! $node instanceof Expression) {
            return null;
        }

        if ($node->expr instanceof MethodCall && $node->expr->var instanceof MethodCall) {
            $nestedChainMethodCalls = $this->betterNodeFinder->findInstanceOf([$node->expr], MethodCall::class);
            if (count($nestedChainMethodCalls) > $this->nestedChainMethodCallLimit) {
                $this->removingExpression = $node;

                return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
            }
        }

        return null;
    }

    public function leaveNode(Node $node): Nop | Node
    {
        if ($node === $this->removingExpression) {
            // keep any node, so we don't remove it permanently
            $nop = new Nop();
            $nop->setAttributes($node->getAttributes());
            return $nop;
        }

        return $node;
    }
}
