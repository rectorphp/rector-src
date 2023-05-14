<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PHPStan\Scope\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PHPStan\Scope\Contract\NodeVisitor\ScopeResolverNodeVisitorInterface;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;

final class ByRefReturnNodeVisitor extends NodeVisitorAbstract implements ScopeResolverNodeVisitorInterface
{
    public function __construct(
        private readonly SimpleCallableNodeTraverser $simpleCallableNodeTraverser
    ) {
    }

    public function enterNode(Node $node): ?Node
    {
        if (! $node instanceof FunctionLike) {
            return null;
        }

        if (! $node->returnsByRef()) {
            return null;
        }

        $stmts = $node->getStmts();
        if ($stmts === null) {
            return null;
        }

        $this->simpleCallableNodeTraverser->traverseNodesWithCallable(
            $stmts,
            static function (Node $subNode): int|null|Return_ {
                if ($subNode instanceof Class_ || $subNode instanceof FunctionLike) {
                    return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
                }

                if (! $subNode instanceof Return_) {
                    return null;
                }

                $subNode->setAttribute(AttributeKey::IS_BYREF_RETURN, true);
                return $subNode;
            }
        );

        return null;
    }
}
