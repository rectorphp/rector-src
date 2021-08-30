<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\LogicalAnd;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PhpParser\Node\Expr\BinaryOp\LogicalAnd;
use PhpParser\Node\Expr\BinaryOp\LogicalOr;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://stackoverflow.com/a/5998330/1348344
 *
 * @see \Rector\Tests\CodeQuality\Rector\LogicalAnd\LogicalToBooleanRector\LogicalToBooleanRectorTest
 */
final class LogicalToBooleanRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change OR, AND to ||, && with more common understanding',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
if ($f = false or true) {
    return $f;
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
if (($f = false) || true) {
    return $f;
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
        return [LogicalOr::class, LogicalAnd::class];
    }

    /**
     * @param LogicalOr|LogicalAnd $node
     */
    public function refactor(Node $node): ?Node
    {
        $left = $node->left;
        if ($left instanceof LogicalOr || $left instanceof LogicalAnd) {
            /** @var BooleanAnd|BooleanOr $left */
            $left = $this->refactor($left);
        }

        $right = $node->right;
        if ($right instanceof LogicalOr || $right instanceof LogicalAnd) {
            /** @var BooleanAnd|BooleanOr $right */
            $right = $this->refactor($right);
        }

        if ($node instanceof LogicalOr) {
            return new BooleanOr($left, $right);
        }
        return new BooleanAnd($left, $right);
    }
}
