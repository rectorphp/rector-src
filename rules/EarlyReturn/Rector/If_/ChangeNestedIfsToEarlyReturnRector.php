<?php

declare(strict_types=1);

namespace Rector\EarlyReturn\Rector\If_;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\NodeManipulator\IfManipulator;
use Rector\Core\Rector\AbstractRector;
use Rector\EarlyReturn\NodeTransformer\ConditionInverter;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\EarlyReturn\Rector\If_\ChangeNestedIfsToEarlyReturnRector\ChangeNestedIfsToEarlyReturnRectorTest
 */
final class ChangeNestedIfsToEarlyReturnRector extends AbstractRector
{
    public function __construct(
        private readonly ConditionInverter $conditionInverter,
        private readonly IfManipulator $ifManipulator
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change nested ifs to early return', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        if ($value === 5) {
            if ($value2 === 10) {
                return 'yes';
            }
        }

        return 'no';
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        if ($value !== 5) {
            return 'no';
        }

        if ($value2 === 10) {
            return 'yes';
        }

        return 'no';
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
        return [Function_::class, ClassMethod::class];
    }

    /**
     * @param Function_|ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        $stmts = $node->stmts;
        if ($stmts === null) {
            return null;
        }

        foreach ($stmts as $key => $stmt) {
            $nextStmt = $stmts[$key + 1] ?? null;
            if (! $nextStmt instanceof Return_) {
                continue;
            }

            if (! $stmt instanceof If_) {
                continue;
            }

            $nestedIfsWithOnlyReturn = $this->ifManipulator->collectNestedIfsWithOnlyReturn($stmt);
            if ($nestedIfsWithOnlyReturn === []) {
                return null;
            }

            $this->processNestedIfsWithOnlyReturn($stmt, $nestedIfsWithOnlyReturn, $nextStmt);
            $this->removeNode($stmt);
        }

        return null;
    }

    /**
     * @param If_[] $nestedIfsWithOnlyReturn
     */
    private function processNestedIfsWithOnlyReturn(If_ $if, array $nestedIfsWithOnlyReturn, Return_ $nextReturn): void
    {
        // add nested if openly after this
        $nestedIfsWithOnlyReturnCount = count($nestedIfsWithOnlyReturn);

        /** @var int $key */
        foreach ($nestedIfsWithOnlyReturn as $key => $nestedIfWithOnlyReturn) {
            // last item → the return node
            if ($nestedIfsWithOnlyReturnCount === $key + 1) {
                $this->nodesToAddCollector->addNodeAfterNode($nestedIfWithOnlyReturn, $if);
            } else {
                $this->addStandaloneIfsWithReturn($nestedIfWithOnlyReturn, $if, $nextReturn);
            }
        }
    }

    private function addStandaloneIfsWithReturn(If_ $nestedIfWithOnlyReturn, If_ $if, Return_ $return): void
    {
        $return = clone $return;

        $invertedCondition = $this->conditionInverter->createInvertedCondition($nestedIfWithOnlyReturn->cond);

        // special case
        if ($invertedCondition instanceof BooleanNot && $invertedCondition->expr instanceof BooleanAnd) {
            $booleanNotPartIf = new If_(new BooleanNot($invertedCondition->expr->left));
            $booleanNotPartIf->stmts = [clone $return];
            $this->nodesToAddCollector->addNodeAfterNode($booleanNotPartIf, $if);

            $secondBooleanNotPartIf = new If_(new BooleanNot($invertedCondition->expr->right));
            $secondBooleanNotPartIf->stmts = [clone $return];
            $this->nodesToAddCollector->addNodeAfterNode($secondBooleanNotPartIf, $if);
            return;
        }

        $nestedIfWithOnlyReturn->cond = $invertedCondition;
        $nestedIfWithOnlyReturn->stmts = [clone $return];

        $this->nodesToAddCollector->addNodeAfterNode($nestedIfWithOnlyReturn, $if);
    }
}
