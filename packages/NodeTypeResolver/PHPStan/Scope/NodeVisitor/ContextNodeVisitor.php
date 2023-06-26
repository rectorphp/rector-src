<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PHPStan\Scope\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Attribute;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\Isset_;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Break_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Do_;
use PhpParser\Node\Stmt\Else_;
use PhpParser\Node\Stmt\ElseIf_;
use PhpParser\Node\Stmt\For_;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Stmt\Unset_;
use PhpParser\Node\Stmt\While_;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PHPStan\Scope\Contract\NodeVisitor\ScopeResolverNodeVisitorInterface;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;

final class ContextNodeVisitor extends NodeVisitorAbstract implements ScopeResolverNodeVisitorInterface
{
    public function __construct(
        private readonly SimpleCallableNodeTraverser $simpleCallableNodeTraverser
    ) {
    }

    public function enterNode(Node $node): ?Node
    {
        if ($node instanceof For_ || $node instanceof Foreach_ || $node instanceof While_ || $node instanceof Do_) {
            $this->processContextInLoop($node);
            return null;
        }

        if ($node instanceof Isset_ || $node instanceof Unset_) {
            $this->processContextInIssetOrUnset($node);
            return null;
        }

        if ($node instanceof Attribute) {
            $this->simpleCallableNodeTraverser->traverseNodesWithCallable(
                $node->args,
                static function (Node $subNode) {
                    if ($subNode instanceof Array_) {
                        $subNode->setAttribute(AttributeKey::IS_ARRAY_IN_ATTRIBUTE, true);
                    }

                    return null;
                }
            );
        }

        if ($node instanceof If_ || $node instanceof Else_ || $node instanceof ElseIf_) {
            $this->processContextInIf($node);
            return null;
        }

        if ($node instanceof Return_ && $node->expr instanceof Expr) {
            $node->expr->setAttribute(AttributeKey::IS_RETURN_EXPR, true);
        }

        if ($node instanceof Arg) {
            $node->value->setAttribute(AttributeKey::IS_ARG_VALUE, true);
        }

        if ($node instanceof Param) {
            $this->processContextParam($node);
        }

        return null;
    }

    private function processContextParam(Param $param): void
    {
        if (! $param->type instanceof Node) {
            return;
        }

        $param->type->setAttribute(AttributeKey::IS_PARAM_TYPE, true);
    }

    private function processContextInIssetOrUnset(Isset_|Unset_ $node): void
    {
        if ($node instanceof Isset_) {
            foreach ($node->vars as $var) {
                $var->setAttribute(AttributeKey::IS_ISSET_VAR, true);
            }

            return;
        }

        foreach ($node->vars as $var) {
            $var->setAttribute(AttributeKey::IS_UNSET_VAR, true);
        }
    }

    private function processContextInIf(If_|Else_|ElseIf_ $node): void
    {
        foreach ($node->stmts as $stmt) {
            if ($stmt instanceof Break_) {
                $stmt->setAttribute(AttributeKey::IS_IN_IF, true);
            }
        }
    }

    private function processContextInLoop(For_|Foreach_|While_|Do_ $node): void
    {
        $this->simpleCallableNodeTraverser->traverseNodesWithCallable(
            $node->stmts,
            static function (Node $subNode): ?int {
                if ($subNode instanceof Class_ || $subNode instanceof Function_ || $subNode instanceof Closure) {
                    return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
                }

                if ($subNode instanceof If_ || $subNode instanceof Break_) {
                    $subNode->setAttribute(AttributeKey::IS_IN_LOOP, true);
                }

                return null;
            }
        );
    }
}
