<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\Stmt;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\BinaryOp\Equal;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Return_;
use Rector\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\Stmt\RemoveEmptyArrayConditionReturnRector\RemoveEmptyArrayConditionReturnRectorTest
 */
final class RemoveEmptyArrayConditionReturnRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove unused empty array condition and return value directly', [
            new CodeSample(
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function __construct(array $items)
    {
        if ($items === []) {
            return [];
        }

        return $items;
    }
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function __construct(array $items)
    {
        if ($items === []) {
            return [];
        }

        return $items;
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
        return [StmtsAwareInterface::class];
    }

    /**
     * @param StmtsAwareInterface $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->stmts === null) {
            return null;
        }

        foreach ($node->stmts as $key => $stmt) {
            if (! $stmt instanceof If_) {
                continue;
            }

            if (count($stmt->stmts) !== 1) {
                continue;
            }

            $soleIfStmt = $stmt->stmts[0];
            if (! $soleIfStmt instanceof Return_) {
                continue;
            }

            if (! $stmt->cond instanceof Identical && ! $stmt->cond instanceof Equal) {
                continue;
            }

            $identicalOrEqual = $stmt->cond;
            $return = $soleIfStmt;

            if (! $this->isEmptyArray($identicalOrEqual->right)) {
                continue;
            }

            $comparedVariable = $identicalOrEqual->left;

            if (! $this->isEmptyArray($return->expr)) {
                continue;
            }

            // next stmt must be return of the same var
            $nextStmt = $node->stmts[$key + 1] ?? null;
            if (! $nextStmt instanceof Return_) {
                continue;
            }

            if (! $nextStmt->expr instanceof Expr) {
                continue;
            }

            if (! $this->nodeComparator->areNodesEqual($nextStmt->expr, $comparedVariable)) {
                continue;
            }

            // remove next stmt
            unset($node->stmts[$key + 1]);

            // replace if with return
            $node->stmts[$key] = $nextStmt;

            return $node;
        }

        return null;
    }

    private function isEmptyArray(?Expr $expr): bool
    {
        if (! $expr instanceof Array_) {
            return false;
        }

        return $expr->items === [];
    }
}
