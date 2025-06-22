<?php

declare(strict_types=1);

namespace Rector\Php84\Rector\Foreach_;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Break_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\If_;
use Rector\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayAllRector\ForeachToArrayAllRectorTest
 */
final class ForeachToArrayAllRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly ValueResolver $valueResolver
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace foreach with boolean assignment and break with array_all',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$found = true;
foreach ($animals as $animal) {
    if (!str_starts_with($animal, 'c')) {
        $found = false;
        break;
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$found = array_all($animals, fn($animal) => str_starts_with($animal, 'c'));
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
    public function refactor(Node $node): ?Node
    {
        if ($node->stmts === null) {
            return null;
        }

        foreach ($node->stmts as $key => $stmt) {
            if (! $stmt instanceof Foreach_) {
                continue;
            }

            $prevStmt = $node->stmts[$key - 1] ?? null;
            if (! $prevStmt instanceof Expression) {
                continue;
            }

            if (! $prevStmt->expr instanceof Assign) {
                continue;
            }

            $foreach = $stmt;
            $prevAssign = $prevStmt->expr;

            if (! $this->valueResolver->isTrue($prevAssign->expr)) {
                continue;
            }

            if (! $prevAssign->var instanceof Variable) {
                continue;
            }

            $assignedVariable = $prevAssign->var;

            if (! $this->isValidForeachStructure($foreach, $assignedVariable)) {
                continue;
            }

            /** @var If_ $firstNodeInsideForeach */
            $firstNodeInsideForeach = $foreach->stmts[0];

            $condition = $firstNodeInsideForeach->cond;
            $valueParam = $foreach->valueVar;

            if (! $valueParam instanceof Variable) {
                continue;
            }
            $param = new Param($valueParam);

            $negatedCondition = $condition instanceof BooleanNot ? $condition->expr : new BooleanNot($condition);

            $arrowFunction = new ArrowFunction([
                'params' => [$param],
                'expr' => $negatedCondition,
            ]);

            $funcCall = $this->nodeFactory->createFuncCall('array_all', [$foreach->expr, $arrowFunction]);

            $newAssign = new Assign($assignedVariable, $funcCall);
            $newExpression = new Expression($newAssign);

            unset($node->stmts[$key - 1]);
            $node->stmts[$key] = $newExpression;

            $node->stmts = array_values($node->stmts);

            return $node;
        }

        return null;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::ARRAY_ALL;
    }

    private function isValidForeachStructure(Foreach_ $foreach, Variable $assignedVariable): bool
    {
        if (count($foreach->stmts) !== 1) {
            return false;
        }

        $firstStmt = $foreach->stmts[0];
        if (
            ! $firstStmt instanceof If_ ||
            count($firstStmt->stmts) !== 2
        ) {
            return false;
        }

        $assignmentStmt = $firstStmt->stmts[0];
        $breakStmt = $firstStmt->stmts[1];

        if (
            ! $assignmentStmt instanceof Expression ||
            ! $assignmentStmt->expr instanceof Assign ||
            ! $breakStmt instanceof Break_
        ) {
            return false;
        }

        $assignment = $assignmentStmt->expr;

        if (! $this->nodeComparator->areNodesEqual($assignment->var, $assignedVariable)) {
            return false;
        }

        return $this->valueResolver->isFalse($assignment->expr);
    }
}
