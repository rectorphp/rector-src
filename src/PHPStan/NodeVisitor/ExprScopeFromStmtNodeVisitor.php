<?php

declare(strict_types=1);

namespace Rector\PHPStan\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\NodeVisitorAbstract;
use PHPStan\Analyser\MutatingScope;
use PHPStan\Node\VirtualNode;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PHPStan\Scope\PHPStanNodeScopeResolver;
use Rector\PhpParser\Node\CustomNode\FileWithoutNamespace;

final class ExprScopeFromStmtNodeVisitor extends NodeVisitorAbstract
{
    private ?Stmt $currentStmt = null;

    public function __construct(
        private readonly PHPStanNodeScopeResolver $phpStanNodeScopeResolver,
        private readonly string $filePath,
        private readonly MutatingScope $mutatingScope
    ) {
    }

    public function enterNode(Node $node): ?Node
    {
        if (
            $node instanceof FileWithoutNamespace ||
            $node instanceof Namespace_ ||
            $node instanceof ClassLike ||
            $node instanceof ClassMethod ||
            $node instanceof Function_) {
            return null;
        }

        if ($node instanceof Stmt) {
            $this->currentStmt = $node;
            return null;
        }

        if ($node instanceof VirtualNode) {
            return null;
        }

        if (! $node instanceof Expr) {
            return null;
        }

        if ($this->shouldSkipExpr($node)) {
            return null;
        }

        $scope = $node->getAttribute(AttributeKey::SCOPE);
        if ($scope instanceof MutatingScope) {
            return null;
        }

        // too deep Expr, eg: $$param = $$bar = self::decodeValue($result->getItem()->getTextContent());
        $scope = $this->currentStmt instanceof Stmt
            ? $this->currentStmt->getAttribute(AttributeKey::SCOPE)
            : $this->mutatingScope;

        $scope = $scope instanceof MutatingScope ? $scope : $this->mutatingScope;

        $node->setAttribute(AttributeKey::SCOPE, $scope);

        if ($node instanceof Closure) {
            $this->phpStanNodeScopeResolver->processNodes($node->stmts, $this->filePath, $scope);
        }

        return null;
    }

    private function shouldSkipExpr(Expr $expr): bool
    {
        return $expr->getAttribute(AttributeKey::EXPRESSION_DEPTH) < 2
            && $expr->getAttribute(AttributeKey::IS_ARG_VALUE) !== true
            && ! ($expr instanceof MethodCall && $expr->name instanceof CallLike);
    }
}
