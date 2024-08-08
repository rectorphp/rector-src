<?php

declare(strict_types=1);

namespace Rector\PHPStan\NodeVisitor;

use PhpParser\Node;
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
        if (! $node instanceof StmtsAwareInterface) {
            return null;
        }

        if ($node->stmts === null) {
            return null;
        }

        $isPassedUnreachableStmt = false;
        $mutatingScope = $this->resolveScope($node->getAttribute(AttributeKey::SCOPE));

        foreach ($node->stmts as $stmt) {
            // has isUnreachable attribute already, continue as scope already set
            if ($stmt->getAttribute(AttributeKey::IS_UNREACHABLE) === true) {
                $isPassedUnreachableStmt = true;
                continue;
            }

            // is stmt after isUnreachable stmt, set isUnreachable attribute and scope
            if ($isPassedUnreachableStmt) {
                $stmt->setAttribute(AttributeKey::IS_UNREACHABLE, true);
                $stmt->setAttribute(AttributeKey::SCOPE, $mutatingScope);

                $this->phpStanNodeScopeResolver->processNodes([$stmt], $this->filePath, $mutatingScope);
            }
        }

        return null;
    }

    private function resolveScope(?Scope $mutatingScope): MutatingScope
    {
        return $mutatingScope instanceof MutatingScope ? $mutatingScope : $this->mutatingScope;
    }
}
