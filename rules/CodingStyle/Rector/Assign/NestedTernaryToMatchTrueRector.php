<?php

declare(strict_types=1);

namespace Rector\CodingStyle\Rector\Assign;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Match_;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\MatchArm;
use Rector\CodingStyle\ValueObject\ConditionAndResult;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodingStyle\Rector\Assign\NestedTernaryToMatchTrueRector\NestedTernaryToMatchTrueRectorTest
 */
final class NestedTernaryToMatchTrueRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Convert nested ternary expressions to match(true) statements', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function getValue($input)
    {
        return $input === 1 ? 'one' : ($input === 2 ? 'two' : 'other');
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function getValue($input)
    {
        return match (true) {
            $input === 1 => 'one',
            $input === 2 => 'two',
            default => 'other',
        };
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
        return [Assign::class];
    }

    /**
     * @param Assign $node
     */
    public function refactor(Node $node): ?Assign
    {
        if (! $node->expr instanceof Ternary) {
            return null;
        }

        $ternary = $node->expr;

        // traverse nested ternaries to collect them all
        $currentTernary = $ternary;

        /** @var ConditionAndResult[] $conditionsAndResults */
        $conditionsAndResults = [];
        $defaultExpr = null;

        while ($currentTernary instanceof Ternary) {
            if ($currentTernary->if === null) {
                // short ternary, skip
                return null;
            }

            $conditionsAndResults[] = new ConditionAndResult($currentTernary->cond, $currentTernary->if);

            $currentTernary = $currentTernary->else;

            if (! $currentTernary instanceof Ternary) {
                $defaultExpr = $currentTernary;
            }
        }

        // nothing long enough
        if (count($conditionsAndResults) < 2 || ! $defaultExpr instanceof Expr) {
            return null;
        }

        $match = $this->createMatch($conditionsAndResults, $defaultExpr);
        $node->expr = $match;

        return $node;
    }

    /**
     * @param ConditionAndResult[] $conditionsAndResults
     */
    private function createMatch(array $conditionsAndResults, Expr $defaultExpr): Match_
    {
        $match = new Match_($this->nodeFactory->createTrue());

        foreach ($conditionsAndResults as $conditionsAndResult) {
            $match->arms[] = new MatchArm([
                $conditionsAndResult->getConditionExpr(),
            ], $conditionsAndResult->getResultExpr());
        }

        $match->arms[] = new MatchArm(null, $defaultExpr);

        return $match;
    }
}
