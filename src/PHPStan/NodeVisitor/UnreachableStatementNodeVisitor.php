<?php

declare(strict_types=1);

namespace Rector\PHPStan\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Declare_;
use PhpParser\NodeVisitorAbstract;
use PHPStan\Analyser\MutatingScope;
use PHPStan\Analyser\Scope;
use Rector\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PHPStan\Scope\PHPStanNodeScopeResolver;

final class UnreachableStatementNodeVisitor extends NodeVisitorAbstract
{
    public function __construct(
        private readonly PHPStanNodeScopeResolver $phpStanNodeScopeResolver,
        private readonly string $filePath,
        private readonly MutatingScope $mutatingScope
    ) {
    }

    public function enterNode(Node $node): ?Node
    {
        if (! $node instanceof StmtsAwareInterface && ! $node instanceof ClassLike && ! $node instanceof Declare_) {
            return null;
        }

        if ($node->stmts === null) {
            return null;
        }

        $isPassedUnreachableStmt = false;
        $mutatingScope = $this->resolveScope($node->getAttribute(AttributeKey::SCOPE));

        foreach ($node->stmts as $stmt) {
            // just being added, set
            if (! $stmt->hasAttribute(AttributeKey::SCOPE)) {
                $stmt->setAttribute(AttributeKey::SCOPE, $mutatingScope);
                $this->phpStanNodeScopeResolver->processNodes([$stmt], $this->filePath, $mutatingScope);
            }

            // has isUnreachable attribute already, continue as scope already set
            if ($stmt->getAttribute(AttributeKey::IS_UNREACHABLE) === true) {
                $isPassedUnreachableStmt = true;
                continue;
            }

            // is stmt after isUnreachable stmt, needs isUnreachable attribute set
            if ($isPassedUnreachableStmt) {
                $stmt->setAttribute(AttributeKey::IS_UNREACHABLE, true);
            }
        }

        return null;
    }

    private function resolveScope(?Scope $mutatingScope): MutatingScope
    {
        return $mutatingScope instanceof MutatingScope ? $mutatingScope : $this->mutatingScope;
    }
}
