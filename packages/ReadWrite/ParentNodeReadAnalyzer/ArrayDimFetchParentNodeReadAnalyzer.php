<?php

declare(strict_types=1);

namespace Rector\ReadWrite\ParentNodeReadAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\ReadWrite\Contract\ParentNodeReadAnalyzerInterface;

final class ArrayDimFetchParentNodeReadAnalyzer implements ParentNodeReadAnalyzerInterface
{
    public function __construct(
        private readonly BetterNodeFinder $betterNodeFinder,
    ) {
    }

    public function isRead(Expr $expr, Node $parentNode): bool
    {
        if (! $parentNode instanceof ArrayDimFetch) {
            return false;
        }

        if ($parentNode->dim !== $expr) {
            return false;
        }

        // is left part of assign
        return $this->isLeftPartOfAssign($expr);
    }

    private function isLeftPartOfAssign(Expr $expr): bool
    {
        $parentAssign = $this->betterNodeFinder->findParentType($expr, Expr\Assign::class);
        if (! $parentAssign instanceof Expr\Assign) {
            return true;
        }

        return ! (bool) $this->betterNodeFinder->findFirst($parentAssign->var, function (\PhpParser\Node $node) use (
            $expr
        ) {
            return $node === $expr;
        });
    }
}
