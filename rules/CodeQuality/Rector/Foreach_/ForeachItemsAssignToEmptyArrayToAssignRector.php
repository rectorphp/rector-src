<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\Foreach_;

use PhpParser\Node\Stmt;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Foreach_;
use PHPStan\Analyser\Scope;
use Rector\CodeQuality\NodeAnalyzer\ForeachAnalyzer;
use Rector\Core\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\Core\Rector\AbstractScopeAwareRector;
use Rector\NodeNestingScope\ValueObject\ControlStructure;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodeQuality\Rector\Foreach_\ForeachItemsAssignToEmptyArrayToAssignRector\ForeachItemsAssignToEmptyArrayToAssignRectorTest
 */
final class ForeachItemsAssignToEmptyArrayToAssignRector extends AbstractScopeAwareRector
{
    public function __construct(
        private readonly ForeachAnalyzer $foreachAnalyzer
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change foreach() items assign to empty array to direct assign',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run($items)
    {
        $collectedItems = [];

        foreach ($items as $item) {
             $collectedItems[] = $item;
        }
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run($items)
    {
        $collectedItems = [];

        $collectedItems = $items;
    }
}
CODE_SAMPLE
                ),
            ]
        );
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
    public function refactorWithScope(Node $node, Scope $scope): ?Node
    {
        if ($node->stmts === null) {
            return null;
        }

        $emptyArrayVariables = [];

        foreach ($node->stmts as $key => $stmt) {
            $variableName = $this->matchEmptyArrayVariableAssign($stmt);
            if (is_string($variableName)) {
                $emptyArrayVariables[] = $variableName;
            }

            if (! $stmt instanceof Foreach_) {
                continue;
            }

            if ($this->shouldSkip($stmt, $emptyArrayVariables)) {
                continue;
            }

            $assignVariable = $this->foreachAnalyzer->matchAssignItemsOnlyForeachArrayVariable($stmt);
            if (! $assignVariable instanceof Expr) {
                continue;
            }

            $directAssign = new Assign($assignVariable, $stmt->expr);
            $node->stmts[$key] = new Expression($directAssign);

            return $node;
        }

        return null;
    }

    /**
     * @param string[] $emptyArrayVariables
     */
    private function shouldSkip(Foreach_ $foreach, array $emptyArrayVariables): bool
    {
        $assignVariable = $this->foreachAnalyzer->matchAssignItemsOnlyForeachArrayVariable($foreach);
        if (! $assignVariable instanceof Expr) {
            return true;
        }

        $foreachedExprType = $this->getType($foreach->expr);

        // only arrays, not traversable/iterable
        if (! $foreachedExprType->isArray()->yes()) {
            return true;
        }

        if ($this->shouldSkipAsPartOfOtherLoop($foreach)) {
            return true;
        }

        return ! $this->isNames($assignVariable, $emptyArrayVariables);
    }

    private function shouldSkipAsPartOfOtherLoop(Foreach_ $foreach): bool
    {
        $foreachParent = $this->betterNodeFinder->findParentByTypes($foreach, ControlStructure::LOOP_NODES);
        return $foreachParent instanceof Node;
    }

    private function matchEmptyArrayVariableAssign(Stmt $stmt): ?string
    {
        if (! $stmt instanceof Expression) {
            return null;
        }

        if (! $stmt->expr instanceof Assign) {
            return null;
        }

        $assign = $stmt->expr;
        if (! $assign->var instanceof Variable) {
            return null;
        }

        // must be assign of empty array
        if (! $this->valueResolver->isValue($assign->expr, [])) {
            return null;
        }

        return $this->getName($assign->var);
    }
}
