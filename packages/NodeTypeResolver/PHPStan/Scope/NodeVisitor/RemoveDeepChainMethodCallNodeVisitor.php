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
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Symplify\PackageBuilder\Parameter\ParameterProvider;

/**
 * Skips performance trap in PHPStan: https://github.com/phpstan/phpstan/issues/254
 */
final class RemoveDeepChainMethodCallNodeVisitor extends NodeVisitorAbstract
{
    /**
     * @var int
     */
    private $nestedChainMethodCallLimit;

    /**
     * @var Expression|null
     */
    private $removingExpression;

    public function __construct(
        private BetterNodeFinder $betterNodeFinder,
        ParameterProvider $parameterProvider
    ) {
        $this->nestedChainMethodCallLimit = (int) $parameterProvider->provideParameter(
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

                return NodeTraverser::DONT_TRAVERSE_CHILDREN;
            }
        }

        return null;
    }

    /**
     * @return Nop|Node
     */
    public function leaveNode(Node $node)
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
