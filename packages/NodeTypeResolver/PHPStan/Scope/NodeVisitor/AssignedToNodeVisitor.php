<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PHPStan\Scope\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\NodeVisitorAbstract;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PHPStan\Scope\Contract\NodeVisitor\ScopeResolverNodeVisitorInterface;

/**
 * Inspired by https://github.com/phpstan/phpstan-src/blob/1.7.x/src/Parser/NewAssignedToPropertyVisitor.php
 */
final class AssignedToNodeVisitor extends NodeVisitorAbstract implements ScopeResolverNodeVisitorInterface
{
    public function enterNode(Node $node): ?Node
    {
        if (! $node instanceof Assign) {
            return null;
        }

        $node->expr->setAttribute(AttributeKey::IS_ASSIGNED_TO, true);
        return null;
    }
}
