<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PHPStan\Scope\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Global_;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use Rector\Core\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;

final class GlobalVariableNodeVisitor extends NodeVisitorAbstract
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

        $globalVariableNames = [];

        foreach ($node->stmts as $stmt) {
            if (! $stmt instanceof Global_) {
                if ($globalVariableNames !== []) {
                    $this->simpleCallableNodeTraverser->traverseNodesWithCallable(
                        $stmt,
                        static function (Node $subNode) use ($globalVariableNames) {
                            if ($subNode instanceof Class_) {
                                return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
                            }

                            if (! $subNode instanceof Variable) {
                                return null;
                            }

                            if (! is_string($subNode->name)) {
                                return null;
                            }

                            if (! in_array($subNode->name, $globalVariableNames, true)) {
                                return null;
                            }

                            $subNode->setAttribute(AttributeKey::IS_GLOBAL_VAR, true);
                            return $subNode;
                        }
                    );
                }

                continue;
            }

            foreach ($stmt->vars as $variable) {
                if ($variable instanceof Variable && is_string($variable->name)) {
                    $variable->setAttribute(AttributeKey::IS_GLOBAL_VAR, true);
                    $globalVariableNames[] = $variable->name;
                }
            }
        }
    }
}
