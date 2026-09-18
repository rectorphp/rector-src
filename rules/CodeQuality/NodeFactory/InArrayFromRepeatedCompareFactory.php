<?php

declare(strict_types=1);

namespace Rector\CodeQuality\NodeFactory;

use PhpParser\Node\Arg;
use Rector\CodeQuality\ValueObject\ComparedExprAndValueExpr;
use Rector\PhpParser\Comparing\NodeComparator;
use Rector\PhpParser\Node\NodeFactory;

final readonly class InArrayFromRepeatedCompareFactory
{
    public function __construct(
        private NodeComparator $nodeComparator,
        private NodeFactory $nodeFactory
    ) {
    }

    /**
     * Builds the "$value, [...]" args of an in_array() call from a repeated compare chain,
     * once all compared expressions are confirmed equal. Returns null when the chain is too
     * short or the compared expressions differ.
     *
     * @param ComparedExprAndValueExpr[] $comparedExprAndValueExprs
     * @return Arg[]|null
     */
    public function createInArrayArgs(array $comparedExprAndValueExprs): ?array
    {
        if (count($comparedExprAndValueExprs) < 3) {
            return null;
        }

        $valueExprs = [];
        foreach ($comparedExprAndValueExprs as $comparedExprAndValueExpr) {
            $valueExprs[] = $comparedExprAndValueExpr->getValueExpr();
        }

        /** @var ComparedExprAndValueExpr $firstComparedExprAndValue */
        $firstComparedExprAndValue = array_pop($comparedExprAndValueExprs);

        // all compared expr must be equal
        foreach ($comparedExprAndValueExprs as $comparedExprAndValueExpr) {
            if (! $this->nodeComparator->areNodesEqual(
                $firstComparedExprAndValue->getComparedExpr(),
                $comparedExprAndValueExpr->getComparedExpr()
            )) {
                return null;
            }
        }

        $array = $this->nodeFactory->createArray($valueExprs);

        return $this->nodeFactory->createArgs([$firstComparedExprAndValue->getComparedExpr(), $array]);
    }
}
