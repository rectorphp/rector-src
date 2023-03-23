<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\If_;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\Core\NodeManipulator\IfManipulator;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodeQuality\Rector\If_\SimplifyIfExactValueReturnValueRector\SimplifyIfExactValueReturnValueRectorTest
 */
final class SimplifyIfExactValueReturnValueRector extends AbstractRector
{
    public function __construct(
        private readonly IfManipulator $ifManipulator
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Changes compared to value and return of expr to direct return',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$value = 'something';
if ($value === 52) {
    return 52;
}

return $value;
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$value = 'something';
return $value;
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
        return [StmtsAwareInterface::class];
    }

    /**
     * @param StmtsAwareInterface $node
     */
    public function refactor(Node $node): ?StmtsAwareInterface
    {
        $hasChanged = false;
        foreach ((array) $node->stmts as $key => $stmt) {
            // on last stmt already
            if (! isset($node->stmts[$key+1])) {
                return null;
            }

            $nextNode = $node->stmts[$key+1];
            if (! $nextNode instanceof Return_) {
                continue;
            }

            $expr = $this->ifManipulator->matchIfValueReturnValue($stmt);
            if (! $expr instanceof Expr) {
                continue;
            }

            if (! $this->nodeComparator->areNodesEqual($expr, $nextNode->expr)) {
                continue;
            }

            unset($node->stmts[$key]);
            $hasChanged = true;
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }
}
