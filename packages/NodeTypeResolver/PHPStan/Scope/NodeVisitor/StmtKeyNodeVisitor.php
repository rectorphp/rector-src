<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PHPStan\Scope\NodeVisitor;

use Attribute;
use PhpParser\Node;
use PhpParser\Node\Stmt\Catch_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Else_;
use PhpParser\Node\Stmt\ElseIf_;
use PhpParser\Node\Stmt\Finally_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\NodeVisitorAbstract;
use Rector\Core\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\Core\Util\MultiInstanceofChecker;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PHPStan\Scope\Contract\NodeVisitor\ScopeResolverNodeVisitorInterface;

final class StmtKeyNodeVisitor extends NodeVisitorAbstract implements ScopeResolverNodeVisitorInterface
{
    /**
     * @var array<class-string<Node>>
     */
    private const INDIRECT_NEXT_NODES = [
        Else_::class,
        ElseIf_::class,
        Catch_::class,
        Finally_::class
    ];

    public function __construct(private readonly MultiInstanceofChecker $multiInstanceofChecker)
    {
    }

    /**
     * @param Node[] $nodes
     * @return Node[]
     */
    public function beforeTraverse(array $nodes): array
    {
        // count = 1 is essential here as FileWithoutNamespace can merge with other Stmt
        if (count($nodes) === 1) {
            $currentNode = current($nodes);
            if ($currentNode instanceof FileWithoutNamespace) {
                foreach ($currentNode->stmts as $key => $stmt) {
                    $stmt->setAttribute(AttributeKey::STMT_KEY, $key);
                }
            }

            if ($currentNode->getAttribute(AttributeKey::STMT_KEY) === null) {
                $currentNode->setAttribute(AttributeKey::STMT_KEY, 0);
            }

            return $nodes;
        }

        foreach ($nodes as $key => $node) {
            $node->setAttribute(AttributeKey::STMT_KEY, $key);
        }

        return $nodes;
    }

    /**
     * @param Node[] $nodes
     * @return Node[]
     */
    public function afterTraverse(array $nodes): array
    {
        foreach ($nodes as $key => $node) {
            if (! $node instanceof Namespace_) {
                return $nodes;
            }

            $node->setAttribute(AttributeKey::STMT_KEY, $key);
        }

        return $nodes;
    }

    public function enterNode(Node $node): ?Node
    {
        if (! $node instanceof StmtsAwareInterface && ! $node instanceof ClassLike) {
            return null;
        }

        if ($node->stmts === null) {
            return null;
        }

        if ($this->multiInstanceofChecker->isInstanceOf($node, self::INDIRECT_NEXT_NODES)) {
            $node->setAttribute(AttributeKey::STMT_KEY, 0);
        }

        // re-index stmt key under current node
        foreach ($node->stmts as $key => $childStmt) {
            $childStmt->setAttribute(AttributeKey::STMT_KEY, $key);
        }

        return null;
    }
}
