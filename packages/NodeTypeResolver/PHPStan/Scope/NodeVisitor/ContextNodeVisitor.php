<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PHPStan\Scope\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Attribute;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\Isset_;
use PhpParser\Node\Stmt\Break_;
use PhpParser\Node\Stmt\Do_;
use PhpParser\Node\Stmt\Else_;
use PhpParser\Node\Stmt\ElseIf_;
use PhpParser\Node\Stmt\For_;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Unset_;
use PhpParser\Node\Stmt\While_;
use PhpParser\NodeVisitorAbstract;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PHPStan\Scope\Contract\NodeVisitor\ScopeResolverNodeVisitorInterface;

final class ContextNodeVisitor extends NodeVisitorAbstract implements ScopeResolverNodeVisitorInterface
{
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
            foreach ($node->args as $arg) {
                if ($arg->value instanceof Array_) {
                    $arg->value->setAttribute(AttributeKey::IS_ARRAY_IN_ATTRIBUTE, true);
                }
            }

            return null;
        }

        if ($node instanceof If_ || $node instanceof Else_ || $node instanceof ElseIf_) {
            $this->processContextInIf($node);
            return null;
        }

        return null;
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
        foreach ($node->stmts as $stmt) {
            if ($stmt instanceof If_ || $stmt instanceof Break_) {
                $stmt->setAttribute(AttributeKey::IS_IN_LOOP, true);
            }
        }
    }
}
