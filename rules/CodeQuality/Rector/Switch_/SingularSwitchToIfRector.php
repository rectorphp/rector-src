<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\Switch_;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Break_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Switch_;
use Rector\Rector\AbstractRector;
use Rector\Renaming\NodeManipulator\SwitchManipulator;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @extends AbstractRector<Switch_>
 * @see \Rector\Tests\CodeQuality\Rector\Switch_\SingularSwitchToIfRector\SingularSwitchToIfRectorTest
 */
final class SingularSwitchToIfRector extends AbstractRector
{
    public function __construct(
        private readonly SwitchManipulator $switchManipulator
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change `switch` with only 1 check to `if`', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeObject
{
    public function run($value)
    {
        $result = 1;
        switch ($value) {
            case 100:
            $result = 1000;
        }

        return $result;
    }
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
class SomeObject
{
    public function run($value)
    {
        $result = 1;
        if ($value === 100) {
            $result = 1000;
        }

        return $result;
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [Switch_::class];
    }

    /**
     * @return Node\Stmt[]|If_|null
     */
    public function refactor(Node $node): array|If_|null
    {
        if (count($node->cases) !== 1) {
            return null;
        }

        $onlyCase = $node->cases[0];

        // only default → basically unwrap
        if (! $onlyCase->cond instanceof Expr) {
            // remove default clause because it cause syntax error
            return array_filter($onlyCase->stmts, static fn (Stmt $stmt): bool => ! $stmt instanceof Break_);
        }

        $if = new If_(new Identical($node->cond, $onlyCase->cond));
        $if->stmts = $this->switchManipulator->removeBreakNodes($onlyCase->stmts);

        return $if;
    }
}
