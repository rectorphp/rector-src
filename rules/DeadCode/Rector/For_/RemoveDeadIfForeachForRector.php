<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\For_;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Else_;
use PhpParser\Node\Stmt\For_;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\If_;
use Rector\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\EarlyReturn\NodeTransformer\ConditionInverter;
use Rector\NodeManipulator\StmtsManipulator;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\For_\RemoveDeadIfForeachForRector\RemoveDeadIfForeachForRectorTest
 */
final class RemoveDeadIfForeachForRector extends AbstractRector
{
    private bool $hasChanged = false;

    public function __construct(
        private readonly ConditionInverter $conditionInverter,
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly StmtsManipulator $stmtsManipulator
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove if, foreach and for that does not do anything',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run($value, $differrentValue)
    {
        if ($value) {
        }

        foreach ($values as $value) {
        }

        return $differentValue;
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run($value, $differrentValue)
    {
        return $differentValue;
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
    public function refactor(Node $node): Node|null
    {
        if ($node->stmts === null) {
            return null;
        }

        $this->hasChanged = false;
        foreach ($node->stmts as $key => $stmt) {
            if (! $stmt instanceof If_ && ! $stmt instanceof For_ && ! $stmt instanceof Foreach_) {
                continue;
            }

            if ($stmt->stmts !== []) {
                continue;
            }

            if ($stmt instanceof If_) {
                $this->processIf($stmt, $key, $node);
                continue;
            }

            $this->processForForeach($stmt, $key, $node);
        }

        if ($this->hasChanged) {
            return $node;
        }

        return null;
    }

    private function processIf(If_ $if, int $key, StmtsAwareInterface $stmtsAware): void
    {
        if ($if->elseifs !== []) {
            return;
        }

        // useless if ()
        if (! $if->else instanceof Else_) {
            if ($this->hasNodeSideEffect($if->cond)) {
                return;
            }

            unset($stmtsAware->stmts[$key]);
            $this->hasChanged = true;
            return;
        }

        $if->cond = $this->conditionInverter->createInvertedCondition($if->cond);
        $if->stmts = $if->else->stmts;
        $if->else = null;

        $this->hasChanged = true;
    }

    private function processForForeach(For_|Foreach_ $for, int $key, StmtsAwareInterface $stmtsAware): void
    {
        if ($for instanceof For_) {
            $variables = $this->betterNodeFinder->findInstanceOf(
                [...$for->init, ...$for->cond, ...$for->loop],
                Variable::class
            );
            foreach ($variables as $variable) {
                if ($this->stmtsManipulator->isVariableUsedInNextStmt(
                    $stmtsAware,
                    $key + 1,
                    (string) $this->getName($variable)
                )) {
                    return;
                }
            }

            unset($stmtsAware->stmts[$key]);
            $this->hasChanged = true;

            return;
        }

        $exprs = [$for->expr, $for->valueVar, $for->valueVar];
        $variables = $this->betterNodeFinder->findInstanceOf($exprs, Variable::class);
        foreach ($variables as $variable) {
            if ($this->stmtsManipulator->isVariableUsedInNextStmt(
                $stmtsAware,
                $key + 1,
                (string) $this->getName($variable)
            )) {
                return;
            }
        }

        unset($stmtsAware->stmts[$key]);
        $this->hasChanged = true;
    }

    private function hasNodeSideEffect(Expr $expr): bool
    {
        return $this->betterNodeFinder->hasInstancesOf($expr, [CallLike::class, Assign::class]);
    }
}
