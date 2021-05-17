<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\Ternary;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\Ternary;
use Rector\Core\NodeManipulator\BinaryOpManipulator;
use Rector\Core\Rector\AbstractRector;
use Rector\Php71\ValueObject\TwoNodeMatch;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodeQuality\Rector\Ternary\SimplifyTautologyTernaryRector\SimplifyTautologyTernaryRectorTest
 */
final class SimplifyTautologyTernaryRector extends AbstractRector
{
    public function __construct(
        private BinaryOpManipulator $binaryOpManipulator
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Simplify tautology ternary to value', [
            new CodeSample(
                '$value = ($fullyQualifiedTypeHint !== $typeHint) ? $fullyQualifiedTypeHint : $typeHint;',
                '$value = $fullyQualifiedTypeHint;'
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
    public function refactor(Node $node): ?Node
    {
        if (! $node->cond instanceof NotIdentical && ! $node->cond instanceof Identical) {
            return null;
        }

        $twoNodeMatch = $this->binaryOpManipulator->matchFirstAndSecondConditionNode(
            $node->cond,
            fn (Node $leftNode): bool => $this->nodeComparator->areNodesEqual($leftNode, $node->if),
            fn (Node $leftNode): bool => $this->nodeComparator->areNodesEqual($leftNode, $node->else)
        );

        if (! $twoNodeMatch instanceof TwoNodeMatch) {
            return null;
        }

        return $node->if;
    }
}
