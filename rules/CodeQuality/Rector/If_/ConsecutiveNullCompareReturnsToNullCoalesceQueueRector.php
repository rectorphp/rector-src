<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\If_;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\Core\NodeManipulator\IfManipulator;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodeQuality\Rector\If_\ConsecutiveNullCompareReturnsToNullCoalesceQueueRector\ConsecutiveNullCompareReturnsToNullCoalesceQueueRectorTest
 */
final class ConsecutiveNullCompareReturnsToNullCoalesceQueueRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly IfManipulator $ifManipulator
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change multiple null compares to ?? queue', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        if ($this->orderItem !== null) {
            return $this->orderItem;
        }

        if ($this->orderItemUnit !== null) {
            return $this->orderItemUnit;
        }

        return null;
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        return $this->orderItem ?? $this->orderItemUnit;
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
        if ($node->stmts === null) {
            return null;
        }

        $coalescingExprs = [];
        $ifKeys = [];

        foreach ($node->stmts as $key => $stmt) {
            if (! $stmt instanceof If_) {
                continue;
            }

            $comparedExpr = $this->ifManipulator->matchIfNotNullReturnValue($stmt);
            if (! $comparedExpr instanceof Expr) {
                continue;
            }

            $coalescingExprs[] = $comparedExpr;
            $ifKeys[] = $key;
        }

        // at least 2 coalescing nodes are needed
        if (count($coalescingExprs) < 2) {
            return null;
        }

        // remove last return null
        foreach ($node->stmts as $key => $stmt) {
            if (in_array($key, $ifKeys, true)) {
                unset($node->stmts[$key]);
                continue;
            }

            if (! $this->isReturnNull($stmt)) {
                continue;
            }

            unset($node->stmts[$key]);
        }

        $node->stmts[] = $this->createCealesceReturn($coalescingExprs);

        return $node;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::NULL_COALESCE;
    }

    private function isReturnNull(Stmt $stmt): bool
    {
        if (! $stmt instanceof Return_) {
            return false;
        }

        if (! $stmt->expr instanceof Expr) {
            return false;
        }

        return $this->valueResolver->isNull($stmt->expr);
    }

    /**
     * @param Expr[] $coalescingExprs
     */
    private function createCealesceReturn(array $coalescingExprs): Return_
    {
        /** @var Expr $leftExpr */
        $leftExpr = array_shift($coalescingExprs);

        /** @var Expr $rightExpr */
        $rightExpr = array_shift($coalescingExprs);

        $coalesce = new Coalesce($leftExpr, $rightExpr);

        foreach ($coalescingExprs as $coalescingExpr) {
            $coalesce = new Coalesce($coalesce, $coalescingExpr);
        }

        return new Return_($coalesce);
    }
}
