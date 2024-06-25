<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver;

use PhpParser\Node\Stmt;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\CloningVisitor;
use Rector\NodeTypeResolver\PHPStan\Scope\PHPStanNodeScopeResolver;
use Rector\PhpParser\NodeTraverser\FileWithoutNamespaceNodeTraverser;

final readonly class NodeScopeAndMetadataDecorator
{
    private NodeTraverser $nodeTraverser;

    public function __construct(
        CloningVisitor $cloningVisitor,
        private PHPStanNodeScopeResolver $phpStanNodeScopeResolver,
        private FileWithoutNamespaceNodeTraverser $fileWithoutNamespaceNodeTraverser,
        private readonly ScopeFactory $scopeFactory,
    ) {
        // for format preserving printing
        $this->nodeTraverser = new NodeTraverser();
        $this->nodeTraverser->addVisitor(new CloningVisitor());
    }

    /**
     * @param Stmt[] $stmts
     * @return Stmt[]
     */
    public function decorateNodesFromFile(string $filePath, array $stmts): array
    {
        $stmts = $this->fileWithoutNamespaceNodeTraverser->traverse($stmts);
        $stmts = $this->phpStanNodeScopeResolver->processNodes($stmts, $filePath);

        return $this->nodeTraverser->traverse($stmts);
    }
}
