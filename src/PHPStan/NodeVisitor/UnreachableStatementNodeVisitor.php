<?php

declare(strict_types=1);

namespace Rector\PHPStan\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Exit_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Declare_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeVisitorAbstract;
use PHPStan\Analyser\MutatingScope;
use PHPStan\Analyser\Scope;
use Rector\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PHPStan\Scope\PHPStanNodeScopeResolver;
use Rector\NodeTypeResolver\PHPStan\Scope\ScopeFactory;

final class UnreachableStatementNodeVisitor extends NodeVisitorAbstract
{
    public function __construct(
        private readonly PHPStanNodeScopeResolver $phpStanNodeScopeResolver,
        private readonly string $filePath,
        private readonly ScopeFactory $scopeFactory
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
            if ($stmt instanceof Expression && $stmt->expr instanceof Exit_) {
                $isPassedUnreachableStmt = true;
                $this->processExitScope($stmt->expr, $stmt, $mutatingScope);

                continue;
            }

            if ($stmt->getAttribute(AttributeKey::IS_UNREACHABLE) === true) {
                $isPassedUnreachableStmt = true;
                continue;
            }

            if ($isPassedUnreachableStmt) {
                $stmt->setAttribute(AttributeKey::IS_UNREACHABLE, true);
                $stmt->setAttribute(AttributeKey::SCOPE, $mutatingScope);
                $this->phpStanNodeScopeResolver->processNodes([$stmt], $this->filePath, $mutatingScope);
            }
        }

        return null;
    }

    private function processExitScope(Exit_ $exit, Expression $expression, MutatingScope $mutatingScope): void
    {
        if ($exit->expr instanceof Expr && ! $exit->expr->getAttribute(AttributeKey::SCOPE) instanceof MutatingScope) {
            $expression->setAttribute(AttributeKey::SCOPE, $mutatingScope);
            $this->phpStanNodeScopeResolver->processNodes([$expression], $this->filePath, $mutatingScope);
        }
    }

    private function resolveScope(?Scope $mutatingScope): MutatingScope
    {
        return $mutatingScope instanceof MutatingScope ? $mutatingScope : $this->scopeFactory->createFromFile(
            $this->filePath
        );
    }
}
