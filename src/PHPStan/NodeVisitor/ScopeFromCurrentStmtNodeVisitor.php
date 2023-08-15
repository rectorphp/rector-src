<?php

declare(strict_types=1);

namespace Rector\Core\PHPStan\NodeVisitor;

use PHPStan\Analyser\Scope;
use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\NodeVisitorAbstract;
use Rector\Core\NodeAnalyzer\ScopeAnalyzer;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class ScopeFromCurrentStmtNodeVisitor extends NodeVisitorAbstract
{
    private ?Stmt $currentStmt = null;

    public function __construct(private readonly ScopeAnalyzer $scopeAnalyzer)
    {
    }

    public function enterNode(Node $node): ?Node
    {
        if ($node instanceof Stmt) {
            $this->currentStmt = $node;
            return null;
        }

        $scope = $node->getAttribute(AttributeKey::SCOPE);
        if ($scope instanceof Scope) {
            return null;
        }

        if ($this->currentStmt instanceof Stmt && $this->scopeAnalyzer->isRefreshable($node)) {
            $node->setAttribute(AttributeKey::SCOPE, $this->currentStmt->getAttribute(AttributeKey::SCOPE));
        }

        return null;
    }
}
