<?php

declare(strict_types=1);

namespace Rector\Php84\Rector\Foreach_;

use PhpParser\Node;
use PhpParser\Node\ContainsStmts;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Break_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Return_;
use Rector\NodeManipulator\StmtsManipulator;
use Rector\Php84\NodeAnalyzer\ForeachKeyUsedInConditionalAnalyzer;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayAnyRector\ForeachToArrayAnyRectorTest
 */
final class ForeachToArrayAnyRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly ValueResolver $valueResolver,
        private readonly ForeachKeyUsedInConditionalAnalyzer $foreachKeyUsedInConditionalAnalyzer,
        private readonly StmtsManipulator $stmtsManipulator,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace foreach with boolean assignment + break OR foreach with early return with array_any',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$found = false;
foreach ($animals as $animal) {
    if (str_starts_with($animal, 'c')) {
        $found = true;
        break;
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$found = array_any($animals, fn($animal) => str_starts_with($animal, 'c'));
CODE_SAMPLE
                ),
                new CodeSample(
                    <<<'CODE_SAMPLE'
foreach ($animals as $animal) {
    if (str_starts_with($animal, 'c')) {
        return true;
    }
}
return false;
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
return array_any($animals, fn($animal) => str_starts_with($animal, 'c'));
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
        return [ContainsStmts::class];
    }

    /**
     * @param ContainsStmts $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->getStmts() === []) {
            return null;
        }

        return $this->refactorBooleanAssignmentPattern($node)
            ?? $this->refactorEarlyReturnPattern($node);
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::ARRAY_ANY;
    }

    private function refactorBooleanAssignmentPattern(ContainsStmts $containsStmts): ?Node
    {
        foreach ($containsStmts->stmts as $key => $stmt) {
            if (! $stmt instanceof Foreach_) {
                continue;
            }

            $prevStmt = $containsStmts->stmts[$key - 1] ?? null;
            if (! $prevStmt instanceof Expression) {
                continue;
            }

            if (! $prevStmt->expr instanceof Assign) {
                continue;
            }

            $foreach = $stmt;
            $prevAssign = $prevStmt->expr;

            if (! $this->valueResolver->isFalse($prevAssign->expr)) {
                continue;
            }

            if (! $prevAssign->var instanceof Variable) {
                continue;
            }

            $assignedVariable = $prevAssign->var;

            if (! $this->isValidBooleanAssignmentForeachStructure($foreach, $assignedVariable)) {
                continue;
            }

            if ($this->stmtsManipulator->isVariableUsedInNextStmt(
                $containsStmts,
                $key + 1,
                (string) $this->getName($foreach->valueVar)
            )) {
                continue;
            }

            /** @var If_ $firstNodeInsideForeach */
            $firstNodeInsideForeach = $foreach->stmts[0];

            $condition = $firstNodeInsideForeach->cond;
            $valueParam = $foreach->valueVar;

            if (! $valueParam instanceof Variable) {
                continue;
            }

            $params = [new Param($valueParam)];

            if ($foreach->keyVar instanceof Variable && $this->foreachKeyUsedInConditionalAnalyzer->isUsed(
                $foreach->keyVar,
                $condition
            )) {
                $params[] = new Param(new Variable((string) $this->getName($foreach->keyVar)));
            }

            $arrowFunction = new ArrowFunction([
                'params' => $params,
                'expr' => $condition,
            ]);

            $funcCall = $this->nodeFactory->createFuncCall('array_any', [$foreach->expr, $arrowFunction]);

            $newAssign = new Assign($assignedVariable, $funcCall);
            $newExpression = new Expression($newAssign);

            unset($containsStmts->stmts[$key - 1]);
            $containsStmts->stmts[$key] = $newExpression;

            $containsStmts->stmts = array_values($containsStmts->stmts);

            return $containsStmts;
        }

        return null;
    }

    private function refactorEarlyReturnPattern(ContainsStmts $containsStmts): ?Node
    {
        foreach ($containsStmts->stmts as $key => $stmt) {
            if (! $stmt instanceof Foreach_) {
                continue;
            }

            $foreach = $stmt;
            $nextStmt = $containsStmts->stmts[$key + 1] ?? null;

            if (! $nextStmt instanceof Return_) {
                continue;
            }

            if (! $nextStmt->expr instanceof Expr) {
                continue;
            }

            if (! $this->valueResolver->isFalse($nextStmt->expr)) {
                continue;
            }

            if (! $this->isValidEarlyReturnForeachStructure($foreach)) {
                continue;
            }

            /** @var If_ $firstNodeInsideForeach */
            $firstNodeInsideForeach = $foreach->stmts[0];
            $condition = $firstNodeInsideForeach->cond;

            $params = [];

            if ($foreach->valueVar instanceof Variable) {
                $params[] = new Param($foreach->valueVar);
            }

            if (
                $foreach->keyVar instanceof Variable &&
                $this->foreachKeyUsedInConditionalAnalyzer->isUsed($foreach->keyVar, $condition)
            ) {
                $params[] = new Param(new Variable((string) $this->getName($foreach->keyVar)));
            }

            $arrowFunction = new ArrowFunction([
                'params' => $params,
                'expr' => $condition,
            ]);

            $funcCall = $this->nodeFactory->createFuncCall('array_any', [$foreach->expr, $arrowFunction]);

            $containsStmts->stmts[$key] = new Return_($funcCall);
            unset($containsStmts->stmts[$key + 1]);
            $containsStmts->stmts = array_values($containsStmts->stmts);

            return $containsStmts;
        }

        return null;
    }

    private function isValidBooleanAssignmentForeachStructure(Foreach_ $foreach, Variable $assignedVariable): bool
    {
        if (count($foreach->getStmts()) !== 1) {
            return false;
        }

        $firstStmt = $foreach->getStmts()[0];
        if (
            ! $firstStmt instanceof If_ ||
            count($firstStmt->getStmts()) !== 2
        ) {
            return false;
        }

        $assignmentStmt = $firstStmt->getStmts()[0];
        $breakStmt = $firstStmt->getStmts()[1];

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

        if (! $this->valueResolver->isTrue($assignment->expr)) {
            return false;
        }

        $type = $this->nodeTypeResolver->getNativeType($foreach->expr);
        return $type->isArray()
            ->yes();
    }

    private function isValidEarlyReturnForeachStructure(Foreach_ $foreach): bool
    {
        if (count($foreach->getStmts()) !== 1) {
            return false;
        }

        if (! $foreach->getStmts()[0] instanceof If_) {
            return false;
        }

        $ifStmt = $foreach->getStmts()[0];

        if (count($ifStmt->getStmts()) !== 1) {
            return false;
        }

        if (! $ifStmt->getStmts()[0] instanceof Return_) {
            return false;
        }

        $returnStmt = $ifStmt->getStmts()[0];

        if (! $returnStmt->expr instanceof Expr) {
            return false;
        }

        if (! $this->valueResolver->isTrue($returnStmt->expr)) {
            return false;
        }

        if (! $foreach->valueVar instanceof Variable) {
            return false;
        }

        $type = $this->nodeTypeResolver->getNativeType($foreach->expr);

        return $type->isArray()
            ->yes();
    }
}
