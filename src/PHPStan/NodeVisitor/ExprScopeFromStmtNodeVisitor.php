<?php

declare(strict_types=1);

namespace Rector\Core\PHPStan\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt;
use PhpParser\NodeVisitorAbstract;
use PHPStan\Analyser\Scope;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PHPStan\Scope\ScopeFactory;

final class ExprScopeFromStmtNodeVisitor extends NodeVisitorAbstract
{
    private ?Stmt $currentStmt = null;

    public function __construct(
        private readonly ScopeFactory $scopeFactory,
        private string $filePath
    ) {
    }

    public function enterNode(Node $node): ?Node
    {
        if ($node instanceof Stmt) {
            $this->currentStmt = $node;
            return null;
        }

        if (! $node instanceof Expr) {
            return null;
        }

        $scope = $node->getAttribute(AttributeKey::SCOPE);
        if ($scope instanceof Scope) {
            return null;
        }

        // too deep Expr, eg: $$param = $$bar = self::decodeValue($result->getItem()->getTextContent());
        if ($node instanceof Expr && $node->getAttribute(AttributeKey::EXPRESSION_DEPTH) >= 2) {
            $filePath = $this->filePath;
            $scope = $this->currentStmt instanceof Stmt
                ? $this->currentStmt->getAttribute(AttributeKey::SCOPE)
                : $this->scopeFactory->createFromFile($filePath);

            $scope = $scope instanceof Scope ? $scope : $this->scopeFactory->createFromFile($filePath);
            $node->setAttribute(AttributeKey::SCOPE, $scope);
        }

        return null;
    }
}
