<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PHPStan\Scope\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\NodeVisitorAbstract;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PHPStan\Scope\Contract\NodeVisitor\ScopeResolverNodeVisitorInterface;

final class ScopeNodeVisitor extends NodeVisitorAbstract implements ScopeResolverNodeVisitorInterface
{
    private ?\PHPStan\Analyser\MutatingScope $scope = null;

    public function enterNode(Node $node): ?Node
    {
        if ($node instanceof Stmt) {
            $this->scope = $node->getAttribute(AttributeKey::SCOPE);
            return null;
        }

        $node->setAttribute(AttributeKey::SCOPE, $this->scope);
    }
}
