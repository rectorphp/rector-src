<?php

declare(strict_types=1);

namespace Rector\PHPStan\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\MatchArm;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Switch_;
use PhpParser\Node\Stmt\TryCatch;
use PhpParser\NodeVisitorAbstract;
use Webmozart\Assert\Assert;

final class ReIndexNodeAttributeVisitor extends NodeVisitorAbstract
{
    public function enterNode(Node $node): ?Node
    {
        if ($node instanceof FunctionLike) {
            /** @var ClassMethod|Function_|Closure $node */
            $node->params = array_values($node->params);

            if ($node instanceof Closure) {
                $node->uses = array_values($node->uses);
            }

            return $node;
        }

        if ($node instanceof CallLike) {
            Assert::propertyExists($node, 'args');
            $node->args = array_values($node->args);
            return $node;
        }

        if ($node instanceof If_) {
            $node->elseifs = array_values($node->elseifs);
            return null;
        }

        if ($node instanceof TryCatch) {
            $node->catches = array_values($node->catches);
            return $node;
        }

        if ($node instanceof Switch_) {
            $node->cases = array_values($node->cases);
            return $node;
        }

        if ($node instanceof MatchArm && is_array($node->conds)) {
            $node->conds = array_values($node->conds);
            return $node;
        }

        return null;
    }
}
