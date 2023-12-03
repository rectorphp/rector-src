<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\BooleanAnd;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\Empty_;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Instanceof_;
use Rector\Core\NodeManipulator\BinaryOpManipulator;
use Rector\Core\Rector\AbstractRector;
use Rector\Php71\ValueObject\TwoNodeMatch;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodeQuality\Rector\BooleanAnd\RemoveUselessIsObjectCheckRector\RemoveUselessIsObjectCheckRectorTest
 */
final class RemoveUselessIsObjectCheckRector extends AbstractRector
{
    public function __construct(
        private readonly BinaryOpManipulator $binaryOpManipulator
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove useless is_object() check on combine with instanceof check',
            [new CodeSample('is_object($obj) && $obj instanceof DateTime', '$obj instanceof DateTime')]
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
        if ($node->left instanceof FuncCall && $this->isName($node->left, 'is_object') && $node->right instanceof Instanceof_) {
            return $this->processRemoveUselessIsObject($node->left, $node->right);
        }

        return null;
    }

    private function processRemoveUselessIsObject(FuncCall $funcCall, Instanceof_ $instanceof): ?Instanceof_
    {
        if ($funcCall->isFirstClassCallable()) {
            return null;
        }

        $args = $funcCall->getArgs();
        if (! isset($args[0])) {
            return null;
        }

        if (! $this->nodeComparator->areNodesEqual($args[0]->value, $instanceof->expr)) {
            return null;
        }

        return $instanceof;
    }
}
