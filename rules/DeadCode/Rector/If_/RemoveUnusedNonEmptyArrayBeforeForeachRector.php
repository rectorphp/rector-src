<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\If_;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\If_;
use PHPStan\Analyser\Scope;
use Rector\Core\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\Core\NodeAnalyzer\PropertyFetchAnalyzer;
use Rector\Core\NodeManipulator\IfManipulator;
use Rector\Core\Php\ReservedKeywordAnalyzer;
use Rector\Core\Rector\AbstractScopeAwareRector;
use Rector\DeadCode\NodeManipulator\CountManipulator;
use Rector\DeadCode\UselessIfCondBeforeForeachDetector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\If_\RemoveUnusedNonEmptyArrayBeforeForeachRector\RemoveUnusedNonEmptyArrayBeforeForeachRectorTest
 */
final class RemoveUnusedNonEmptyArrayBeforeForeachRector extends AbstractScopeAwareRector
{
    public function __construct(
        private readonly CountManipulator $countManipulator,
        private readonly IfManipulator $ifManipulator,
        private readonly UselessIfCondBeforeForeachDetector $uselessIfCondBeforeForeachDetector,
        private readonly ReservedKeywordAnalyzer $reservedKeywordAnalyzer,
        private readonly PropertyFetchAnalyzer $propertyFetchAnalyzer
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove unused if check to non-empty array before foreach of the array',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $values = [];
        if ($values !== []) {
            foreach ($values as $value) {
                echo $value;
            }
        }
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $values = [];
        foreach ($values as $value) {
            echo $value;
        }
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
        return [If_::class, StmtsAwareInterface::class];
    }

    /**
     * @param If_|StmtsAwareInterface $node
     * @return Stmt[]|Foreach_|StmtsAwareInterface|null
     */
    public function refactorWithScope(Node $node, Scope $scope): array|Node|null
    {
        if ($node instanceof If_) {
            if (! $this->isUselessBeforeForeachCheck($node, $scope)) {
                return null;
            }

            /** @var Foreach_ $stmt */
            $stmt = $node->stmts[0];
            $ifComments = $node->getAttribute(AttributeKey::COMMENTS) ?? [];
            $stmtComments = $stmt->getAttribute(AttributeKey::COMMENTS) ?? [];

            $comments = array_merge($ifComments, $stmtComments);
            $stmt->setAttribute(AttributeKey::COMMENTS, $comments);

            return $stmt;
        }

        return $this->refactorStmtsAware($node, false);
    }

    private function isUselessBeforeForeachCheck(If_ $if, Scope $scope): bool
    {
        if (! $this->ifManipulator->isIfWithOnly($if, Foreach_::class)) {
            return false;
        }

        /** @var Foreach_ $foreach */
        $foreach = $if->stmts[0];
        $foreachExpr = $foreach->expr;

        if ($foreachExpr instanceof Variable) {
            $variableName = $this->nodeNameResolver->getName($foreachExpr);
            if (is_string($variableName) && $this->reservedKeywordAnalyzer->isNativeVariable($variableName)) {
                return false;
            }
        }

        $ifCond = $if->cond;
        if ($ifCond instanceof BooleanAnd) {
            return $this->isUselessBooleanAnd($ifCond, $foreachExpr);
        }

        if (($ifCond instanceof Variable || $this->propertyFetchAnalyzer->isPropertyFetch($ifCond))
            && $this->nodeComparator->areNodesEqual($ifCond, $foreachExpr)
        ) {
            $ifType = $scope->getType($ifCond);
            return $ifType->isArray()
                ->yes();
        }

        if ($this->uselessIfCondBeforeForeachDetector->isMatchingNotIdenticalEmptyArray($if, $foreachExpr)) {
            return true;
        }

        if ($this->uselessIfCondBeforeForeachDetector->isMatchingNotEmpty($if, $foreachExpr, $scope)) {
            return true;
        }

        return $this->countManipulator->isCounterHigherThanOne($if->cond, $foreachExpr);
    }

    private function isUselessBooleanAnd(BooleanAnd $booleanAnd, Expr $foreachExpr): bool
    {
        if (! $booleanAnd->left instanceof Variable) {
            return false;
        }

        if (! $this->nodeComparator->areNodesEqual($booleanAnd->left, $foreachExpr)) {
            return false;
        }

        return $this->countManipulator->isCounterHigherThanOne($booleanAnd->right, $foreachExpr);
    }

    private function refactorStmtsAware(StmtsAwareInterface $stmtsAware, bool $hasChanged, int $jumpToKey = 0): ?StmtsAwareInterface
    {
        if ($stmtsAware->stmts === null) {
            return null;
        }

        $totalKeys = array_key_last($stmtsAware->stmts);
        for ($key = $jumpToKey; $key < $totalKeys; ++$key) {
            if (! isset($stmtsAware->stmts[$key], $stmtsAware->stmts[$key + 1])) {
                break;
            }

            $stmt = $stmtsAware->stmts[$key];
            $nextStmt = $stmtsAware->stmts[$key + 1];

            if (! $stmt instanceof If_) {
                continue;
            }

            $nextStmt = $stmtsAware->stmts[$key + 1] ?? null;
            if (! $nextStmt instanceof Foreach_) {
                continue;
            }

            if (! $this->uselessIfCondBeforeForeachDetector->isMatchingEmptyAndForeachedExpr(
                $stmt,
                $nextStmt->expr
            )) {
                continue;
            }

            unset($stmtsAware->stmts[$key]);

            $hasChanged = true;

            return $this->refactorStmtsAware($stmtsAware, $hasChanged, $key + 2);
        }

        if ($hasChanged) {
            return $stmtsAware;
        }

        return null;
    }
}
