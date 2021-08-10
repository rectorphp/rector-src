<?php

declare(strict_types=1);

namespace Rector\Core\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Global_;
use PhpParser\Node\Stmt\Static_;
use Rector\Core\PhpParser\Comparing\NodeComparator;
use Rector\Core\PhpParser\Node\BetterNodeFinder;

final class VariableAnalyzer
{
    public function __construct(
        private BetterNodeFinder $betterNodeFinder,
        private NodeComparator $nodeComparator
    ) {
    }

    public function isStaticOrGlobal(Variable $variable): bool
    {
        return (bool) $this->betterNodeFinder->findFirstPreviousOfNode($variable, function (Node $n) use (
            $variable
        ): bool {
            if (! $n instanceof Static_ && ! $n instanceof Global_) {
                return false;
            }

            foreach ($n->vars as $staticVar) {
                $staticVarVariable = $n instanceof Static_
                    ? $staticVar->var
                    : $staticVar;

                if ($this->nodeComparator->areNodesEqual($staticVarVariable, $variable)) {
                    return true;
                }
            }

            return false;
        });
    }
}
