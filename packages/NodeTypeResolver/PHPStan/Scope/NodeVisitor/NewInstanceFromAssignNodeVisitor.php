<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PHPStan\Scope\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Clone_;
use PhpParser\Node\Expr\New_;
use PhpParser\NodeVisitorAbstract;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PHPStan\Scope\Contract\NodeVisitor\ScopeResolverNodeVisitorInterface;

final class NewInstanceFromAssignNodeVisitor extends NodeVisitorAbstract implements ScopeResolverNodeVisitorInterface
{
    public function enterNode(Node $node): ?Node
    {
        if (! $node instanceof Assign) {
            return null;
        }

        if ($node->expr instanceof New_ || $node->expr instanceof Clone_) {
            $node->var->setAttribute(AttributeKey::IS_NEW_INSTANCE_FROM_ASSIGN, true);
        }

        return null;
    }
}
