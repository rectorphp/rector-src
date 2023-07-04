<?php

declare(strict_types=1);

namespace Rector\Core\PHPStan\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Declare_;
use PhpParser\NodeVisitorAbstract;
use Rector\Core\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\Core\ValueObject\Application\File;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PHPStan\Scope\PHPStanNodeScopeResolver;
use Rector\NodeTypeResolver\PHPStan\Scope\ScopeFactory;

final class UnreachableStatementNodeVisitor extends NodeVisitorAbstract
{
    public function __construct(
        private readonly ScopeFactory $scopeFactory,
        private readonly CurrentFileProvider $currentFileProvider,
        private readonly PHPStanNodeScopeResolver $phpStanNodeScopeResolver
    )
    {
    }

    public function enterNode(Node $node): ?Node
    {
        if (! $node instanceof StmtsAwareInterface && ! $node instanceof ClassLike && ! $node instanceof Declare_) {
            return null;
        }

        if ($node->stmts === null) {
            return null;
        }

        $file = $this->currentFileProvider->getFile();
        if (! $file instanceof File) {
            return null;
        }

        $filePath = $file->getFilePath();
        $isPassedUnreachableStmt = false;
        $mutatingScope ??= $node->getAttribute(AttributeKey::SCOPE);

        foreach ($node->stmts as $stmt) {
            if ($stmt->getAttribute(AttributeKey::IS_UNREACHABLE) === true) {
                $this->phpStanNodeScopeResolver->processNodes([$stmt], $filePath, $mutatingScope);

                $isPassedUnreachableStmt = true;
                continue;
            }

            if ($isPassedUnreachableStmt) {
                $stmt->setAttribute(AttributeKey::SCOPE, $mutatingScope);
                $this->phpStanNodeScopeResolver->processNodes([$stmt], $filePath, $mutatingScope);
            }
        }

        return null;
    }
}
