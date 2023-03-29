<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\Switch_;

use PhpParser\Node;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodeQuality\Rector\Switch_\SwitchTrueToIfRector\SwitchTrueToIfRectorTest
 */
final class SwitchTrueToIfRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change switch (true) to if statements', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        switch (true) {
            case $value === 0:
                return 'no';
            case $value === 1:
                return 'yes';
            case $value === 2:
                return 'maybe';
        };
    }
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        if ($value === 0) {
            return 'no';
        }

        if ($value === 1) {
            return 'yes';
        }

        if ($value === 2) {
            return 'maybe';
        }
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
        return [\PhpParser\Node\Stmt\Switch_::class];
    }

    /**
     * @param \PhpParser\Node\Stmt\Switch_ $node
     * @return Node\Stmt\If_[]
     */
    public function refactor(Node $node): ?array
    {
        if (! $this->valueResolver->isTrue($node->cond)) {
            return null;
        }

        $ifs = [];

        $defaultCase = null;

        foreach ($node->cases as $case) {
            if (! $case->cond instanceof Node\Expr) {
                $defaultCase = $case;
                return null;
            }

            $if = new Node\Stmt\If_($case->cond);
            $if->stmts = $case->stmts;

            $ifs[] = $if;
        }

        return $ifs;
    }
}
