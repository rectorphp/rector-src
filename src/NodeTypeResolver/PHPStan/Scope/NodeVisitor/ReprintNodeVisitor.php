<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PHPStan\Scope\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\BooleanNot;
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
            if ($node->left instanceof BinaryOp  && $node->left->hasAttribute(AttributeKey::ORIGINAL_NODE)) {
                $node->left->setAttribute(AttributeKey::ORIGINAL_NODE, null);
            }

            if ($node->right instanceof BinaryOp && $node->right->hasAttribute(AttributeKey::ORIGINAL_NODE)) {
                $node->right->setAttribute(AttributeKey::ORIGINAL_NODE, null);
            }

            return $node;
        }

        if ($node instanceof BooleanNot && $node->expr instanceof BinaryOp && $node->expr->hasAttribute(AttributeKey::ORIGINAL_NODE)) {
            $node->expr->setAttribute(AttributeKey::WRAPPED_IN_PARENTHESES, true);
        }

        return null;
    }
}
