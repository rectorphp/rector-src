<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\BooleanAnd;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\Empty_;
use PhpParser\Node\Expr\FuncCall;
use Rector\Core\NodeManipulator\BinaryOpManipulator;
use Rector\Core\Rector\AbstractRector;
use Rector\Php71\ValueObject\TwoNodeMatch;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://3v4l.org/EZ2P4
 * @changelog https://3v4l.org/egtb5
 * @see \Rector\Tests\CodeQuality\Rector\BooleanAnd\SimplifyEmptyArrayCheckRector\SimplifyEmptyArrayCheckRectorTest
 */
final class SimplifyEmptyArrayCheckRector extends AbstractRector
{
    public function __construct(
        private readonly BinaryOpManipulator $binaryOpManipulator
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Simplify `is_array` and `empty` functions combination into a simple identical check for an empty array',
            [new CodeSample('is_array($values) && empty($values)', '$values === []')]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [BooleanAnd::class];
    }

    /**
     * @param BooleanAnd $node
     */
    public function refactor(Node $node): ?Node
    {
        $leftNode = $node->left;
        $twoNodeMatch = $this->binaryOpManipulator->matchFirstAndSecondConditionNode(
            $node,
            // is_array(...)
            function (Node $node) use ($leftNode): bool {
                if (! $node instanceof FuncCall) {
                    return false;
                }

                if (! $this->isName($node, 'is_array')) {
                    return false;
                }

                if ($leftNode instanceof Empty_) {
                    return $this->nodeComparator->areNodesEqual($leftNode->expr, $node->args[0]->value);
                }

                return true;
            },
            Empty_::class
        );

        if (! $twoNodeMatch instanceof TwoNodeMatch) {
            return null;
        }

        /** @var Empty_ $emptyOrNotIdenticalNode */
        $emptyOrNotIdenticalNode = $twoNodeMatch->getSecondExpr();

        return new Identical($emptyOrNotIdenticalNode->expr, new Array_());
    }
}
