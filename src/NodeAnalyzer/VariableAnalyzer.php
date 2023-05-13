<?php

declare(strict_types=1);

namespace Rector\Core\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Expr\AssignRef;
use PhpParser\Node\Expr\ClosureUse;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Static_;
use PhpParser\Node\Stmt\StaticVar;
use Rector\Core\PhpParser\Comparing\NodeComparator;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class VariableAnalyzer
{
    public function __construct(
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly NodeComparator $nodeComparator
    ) {
    }

    public function isStaticOrGlobal(Variable $variable): bool
    {
        if ($variable->getAttribute(AttributeKey::IS_GLOBAL_VAR) === true) {
            return true;
        }

        if ($this->isParentStatic($variable)) {
            return true;
        }

        return (bool) $this->betterNodeFinder->findFirstPrevious($variable, function (Node $node) use (
            $variable
        ): bool {
            if (! $node instanceof Static_) {
                return false;
            }

            $vars = $node->vars;
            foreach ($vars as $var) {
                if ($this->nodeComparator->areNodesEqual($var->var, $variable)) {
                    return true;
                }
            }

            return false;
        });
    }

    public function isUsedByReference(Variable $variable): bool
    {
        return (bool) $this->betterNodeFinder->findFirstPrevious($variable, function (Node $subNode) use (
            $variable
        ): bool {
            if ($this->isParamReferenced($subNode, $variable)) {
                return true;
            }

            if (! $subNode instanceof Variable) {
                return false;
            }

            if (! $this->nodeComparator->areNodesEqual($subNode, $variable)) {
                return false;
            }

            $parentNode = $subNode->getAttribute(AttributeKey::PARENT_NODE);
            if ($parentNode instanceof ClosureUse) {
                return $parentNode->byRef;
            }

            return $parentNode instanceof AssignRef;
        });
    }

    private function isParentStatic(Variable $variable): bool
    {
        $parentNode = $variable->getAttribute(AttributeKey::PARENT_NODE);

        if (! $parentNode instanceof Node) {
            return false;
        }

        if (! $parentNode instanceof StaticVar) {
            return false;
        }

        $parentParentNode = $parentNode->getAttribute(AttributeKey::PARENT_NODE);
        return $parentParentNode instanceof Static_;
    }

    private function isParamReferenced(Node $node, Variable $variable): bool
    {
        if (! $node instanceof Param) {
            return false;
        }

        if (! $this->nodeComparator->areNodesEqual($node->var, $variable)) {
            return false;
        }

        return $node->byRef;
    }
}
