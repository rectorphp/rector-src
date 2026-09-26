<?php

declare(strict_types=1);

namespace Rector\PhpParser\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\FunctionLike;
use PhpParser\NodeVisitorAbstract;
use Rector\Analyzer\SimpleScope\SimpleScopeResolver;
use Rector\Contract\PhpParser\DecoratingNodeVisitorInterface;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;

/**
 * Resolves a PHPStan-free SimpleScope per function-like and attaches it to the
 * statements inside, so rules can read the caller type without walking the tree.
 */
final class SimpleScopeNodeVisitor extends NodeVisitorAbstract implements DecoratingNodeVisitorInterface
{
    public function __construct(
        private readonly SimpleScopeResolver $simpleScopeResolver,
        private readonly SimpleCallableNodeTraverser $simpleCallableNodeTraverser
    ) {
    }

    public function enterNode(Node $node): ?Node
    {
        if (! $node instanceof FunctionLike) {
            return null;
        }

        $stmts = $node->getStmts();
        if ($stmts === null) {
            return null;
        }

        $simpleScope = $this->simpleScopeResolver->resolve([$node]);
        $this->simpleCallableNodeTraverser->traverseNodesWithCallable($stmts, static function (Node $subNode) use (
            $simpleScope
        ): null {
            $subNode->setAttribute(AttributeKey::SIMPLE_SCOPE, $simpleScope);
            return null;
        });

        return null;
    }
}
