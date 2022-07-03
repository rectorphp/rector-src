<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\StmtsAwareInterface;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Expression;
use Rector\Core\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\Core\NodeAnalyzer\VariableAnalyzer;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveJustVariableAssignRector\RemoveJustVariableAssignRectorTest
 */
final class RemoveJustVariableAssignRector extends AbstractRector
{
    public function __construct(
        private VariableAnalyzer $variableAnalyzer
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove variable just to assign value or return value', [
            new CodeSample(
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run()
    {
        $result = 100;

        $this->temporaryValue = $result;
    }
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run()
    {
        $this->temporaryValue = 100;
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [StmtsAwareInterface::class];
    }

    /**
     * @param StmtsAwareInterface $node
     */
    public function refactor(Node $node): ?Node
    {
        $stmts = (array) $node->stmts;
        if ($stmts === []) {
            return null;
        }

        $originalStmts = $stmts;

        foreach ($stmts as $key => $stmt) {
            $nextStmt = $stmts[$key + 1] ?? null;

            if (! $stmt instanceof Expression) {
                continue;
            }

            if (! $stmt->expr instanceof Assign) {
                continue;
            }

            $currentAssign = $stmt->expr;

            if (! $nextStmt instanceof Expression) {
                continue;
            }

            if (! $nextStmt->expr instanceof Assign) {
                continue;
            }

            $nextAssign = $nextStmt->expr;

            if (! $this->areTwoVariablesCrossAssign($currentAssign, $nextAssign)) {
                continue;
            }

            // ...
            $currentAssign->var = $nextAssign->var;
            unset($stmts[$key + 1]);
        }

        if ($originalStmts === $stmts) {
            return null;
        }

        $node->stmts = $stmts;

        return $node;
    }

    /**
     * This detects if two variables are cross assigned:
     *
     * $<some> = 1000;
     * $this->value = $<some>;
     */
    private function areTwoVariablesCrossAssign(Assign $currentAssign, Assign $nextAssign): bool
    {
        // is just re-assign to variable
        if (! $currentAssign->var instanceof Variable) {
            return false;
        }

        if (! $nextAssign->expr instanceof Variable) {
            return false;
        }

        if (! $this->nodeComparator->areNodesEqual($currentAssign->var, $nextAssign->expr)) {
            return false;
        }

        if ($this->variableAnalyzer->isUsedByReference($currentAssign->var)) {
            return false;
        }

        return ! $this->variableAnalyzer->isUsedByReference($nextAssign->expr);
    }
}
