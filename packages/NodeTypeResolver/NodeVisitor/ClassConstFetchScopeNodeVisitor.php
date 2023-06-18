<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Stmt;
use PhpParser\NodeVisitorAbstract;
use PHPStan\Analyser\Scope;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class ClassConstFetchScopeNodeVisitor extends NodeVisitorAbstract
{
    private ?Scope $scope = null;

    public function enterNode(Node $node): ?Node
    {
        if ($node instanceof Stmt) {
            $this->scope = $node->getAttribute(AttributeKey::SCOPE);
            return null;
        }

        if (! $node instanceof ClassConstFetch) {
            return null;
        }

        $node->class->setAttribute(AttributeKey::SCOPE, $this->scope);
        $node->name->setAttribute(AttributeKey::SCOPE, $this->scope);

        return null;
    }
}
