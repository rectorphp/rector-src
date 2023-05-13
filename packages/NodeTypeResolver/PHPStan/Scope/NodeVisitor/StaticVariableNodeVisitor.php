<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PHPStan\Scope\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Static_;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use Rector\Core\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;

final class StaticVariableNodeVisitor extends NodeVisitorAbstract
{
    public function __construct(
        private readonly SimpleCallableNodeTraverser $simpleCallableNodeTraverser
    ) {
    }

    public function enterNode(Node $node): ?Node
    {
        if (! $node instanceof StmtsAwareInterface) {
            return null;
        }

        if ($node->stmts === null) {
            return null;
        }

        /** @var string[] $staticVariableNames */
        $staticVariableNames = [];

        foreach ($node->stmts as $stmt) {
            if (! $stmt instanceof Static_) {
                $this->setIsStaticVarAttribute($stmt, $staticVariableNames);
                continue;
            }

            foreach ($stmt->vars as $staticVar) {
                if (is_string($staticVar->var->name)) {
                    $staticVar->var->setAttribute(AttributeKey::IS_STATIC_VAR, true);
                    $staticVariableNames[] = $staticVar->var->name;
                }
            }
        }

        return null;
    }

    /**
     * @param string[] $staticVariableNames
     */
    private function setIsStaticVarAttribute(Stmt $stmt, array $staticVariableNames): void
    {
        if ($staticVariableNames === []) {
            return;
        }

        $this->simpleCallableNodeTraverser->traverseNodesWithCallable(
            $stmt,
            static function (Node $subNode) use ($staticVariableNames): int|null|Variable {
                if ($subNode instanceof Class_) {
                    return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
                }

                if (! $subNode instanceof Variable) {
                    return null;
                }

                if (! is_string($subNode->name)) {
                    return null;
                }

                if (! in_array($subNode->name, $staticVariableNames, true)) {
                    return null;
                }

                $subNode->setAttribute(AttributeKey::IS_STATIC_VAR, true);
                return $subNode;
            }
        );
    }
}
