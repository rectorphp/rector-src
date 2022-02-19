<?php

declare(strict_types=1);

namespace Rector\NodeNestingScope;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Stmt\Expression;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class FlowOfControlLocator
{
    public function resolveNestingHashFromFunctionLike(FunctionLike $functionLike, Node $checkedNode): string
    {
        $nestingHash = spl_object_hash($functionLike) . '__';

        $currentNode = $checkedNode;
        $previous = $currentNode;
        while ($currentNode = $currentNode->getAttribute(AttributeKey::PARENT_NODE)) {
            if ($currentNode instanceof Expression) {
                continue;
            }

            if (! $currentNode instanceof Node) {
                break;
            }

            if ($functionLike === $currentNode) {
                // to high
                break;
            }

            $nestingHash .= $this->resolveBinaryOpNestingHash($currentNode, $previous);
            $nestingHash .= spl_object_hash($currentNode);

            $previous = $currentNode;
        }

        return $nestingHash;
    }

    private function resolveBinaryOpNestingHash(Node $currentNode, Node $previous): string
    {
        if (! $currentNode instanceof BinaryOp) {
            return '';
        }

        // left && right have differnt nesting
        if ($currentNode->left === $previous) {
            return 'binary_left__';
        }

        if ($currentNode->right === $previous) {
            return 'binary_right__';
        }

        return '';
    }
}
