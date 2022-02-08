<?php

declare(strict_types=1);

namespace Rector\DowngradePhp72\NodeManipulator;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\BitwiseOr;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;
use Rector\Core\PhpParser\Comparing\NodeComparator;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class JsonConstCleaner
{
    public function __construct(
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly NodeComparator $nodeComparator
    ) {
    }

    /**
     * @param string[] $constants
     */
    public function clean(ConstFetch|BitwiseOr $node, array $constants): ConstFetch|Expr|null
    {
        if ($node instanceof ConstFetch) {
            return $this->cleanByConstFetch($node, $constants);
        }

        return $this->cleanByBitwiseOr($node, $constants);
    }

    /**
     * @param string[] $constants
     */
    private function cleanByConstFetch(ConstFetch $constFetch, array $constants): ?ConstFetch
    {
        if (! $this->nodeNameResolver->isNames($constFetch, $constants)) {
            return null;
        }

        $parent = $constFetch->getAttribute(AttributeKey::PARENT_NODE);
        if (! $parent instanceof BitwiseOr) {
            return new ConstFetch(new Name('0'));
        }

        return null;
    }

    /**
     * @param string[] $constants
     */
    private function cleanByBitwiseOr(BitwiseOr $bitwiseOr, array $constants): ?Expr
    {
        $zeroConstFetch = new ConstFetch(new Name('0'));
        $transformed = false;

        if ($bitwiseOr->left instanceof ConstFetch && $this->nodeNameResolver->isNames(
            $bitwiseOr->left,
            $constants
        )) {
            $transformed = true;
            $bitwiseOr->left = $zeroConstFetch;
        }

        if ($bitwiseOr->right instanceof ConstFetch && $this->nodeNameResolver->isNames(
            $bitwiseOr->right,
            $constants
        )) {
            $transformed = true;
            $bitwiseOr->right = $zeroConstFetch;
        }

        if (! $transformed) {
            return null;
        }

        if ($this->nodeComparator->areNodesEqual($bitwiseOr->left, $bitwiseOr->right)) {
            return $zeroConstFetch;
        }

        return $this->nodeComparator->areNodesEqual($bitwiseOr->left, $zeroConstFetch)
            ? $bitwiseOr->right
            : $bitwiseOr->left;
    }
}
