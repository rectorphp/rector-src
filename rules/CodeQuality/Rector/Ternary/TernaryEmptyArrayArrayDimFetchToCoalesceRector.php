<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\Ternary;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\Empty_;
use PhpParser\Node\Expr\Ternary;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodeQuality\Rector\Ternary\TernaryEmptyArrayArrayDimFetchToCoalesceRector\TernaryEmptyArrayArrayDimFetchToCoalesceRectorTest
 */
final class TernaryEmptyArrayArrayDimFetchToCoalesceRector extends AbstractRector implements MinPhpVersionInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change ternary empty on array property with array dim fetch to coalesce operator', [
            new CodeSample(
                <<<'CODE_SAMPLE'
final class SomeClass
{
    private array $items = [];

    public function run()
    {
        return ! empty($this->items) ? $this->items[0] : 'default';
    }
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
final class SomeClass
{
    private array $items = [];

    public function run()
    {
        return $this->items[0] ?? 'default';
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
        return [Ternary::class];
    }

    /**
     * @param Ternary $node
     */
    public function refactor(Node $node): Ternary|Coalesce|null
    {
        if (! $node->cond instanceof BooleanNot) {
            return null;
        }

        $negagedExpr = $node->cond->expr;
        if (! $negagedExpr instanceof Empty_) {
            return null;
        }

        if (! $node->if instanceof ArrayDimFetch) {
            return null;
        }

        $emptyExprType = $this->getType($negagedExpr->expr);
        if ($emptyExprType->isArray()->yes()) {
            $dimFetchVar = $node->if->var;
            if (! $this->nodeComparator->areNodesEqual($negagedExpr->expr, $dimFetchVar)) {
                return null;
            }

            return new Coalesce($node->if, $node->else);
        }

        if (! $this->nodeComparator->areNodesEqual($negagedExpr->expr, $node->if)) {
            return null;
        }

        return new Ternary($node->if, null, $node->else);
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::NULL_COALESCE;
    }
}
