<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PHPStan\Scope\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\NodeVisitorAbstract;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PHPStan\Scope\Contract\NodeVisitor\ScopeResolverNodeVisitorInterface;

final class ReprintNodeVisitor extends NodeVisitorAbstract implements ScopeResolverNodeVisitorInterface
{
    public function enterNode(Node $node): ?Node
    {
        if ($node->hasAttribute(AttributeKey::ORIGINAL_NODE)) {
            return null;
        }

        if ($node instanceof BinaryOp) {
            if ($node->left instanceof BinaryOp) {
                $node->left->setAttribute(AttributeKey::ORIGINAL_NODE, null);
            }

            if ($node->right instanceof BinaryOp) {
                $node->right->setAttribute(AttributeKey::ORIGINAL_NODE, null);
            }

            return $node;
        }

        return null;
    }
}
