<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\If_;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\If_;
use PHPStan\Type\Constant\ConstantBooleanType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\RemoveAlwaysTrueIfConditionRectorTest
 */
final class RemoveAlwaysTrueIfConditionRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove if condition that is always true', [
            new CodeSample(
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function go()
    {
        if (1 === 1) {
            return 'yes';
        }

        return 'no';
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function go()
    {
        return 'yes';

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
        return [If_::class];
    }

    /**
     * @param If_ $node
     * @return If_|null|Stmt[]
     */
    public function refactor(Node $node): If_|null|array
    {
        if ($node->else !== null) {
            return null;
        }

        // just one if
        if ($node->elseifs !== []) {
            return null;
        }

        $conditionStaticType = $this->getType($node->cond);
        if (! $conditionStaticType instanceof ConstantBooleanType) {
            return null;
        }

        if (! $conditionStaticType->getValue()) {
            return null;
        }

        if ($node->stmts === []) {
            $this->removeNode($node);
            return null;
        }

        return $node->stmts;
    }
}
