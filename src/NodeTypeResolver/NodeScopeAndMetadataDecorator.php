<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver;

use PhpParser\Node\Stmt;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\CloningVisitor;
use Rector\NodeTypeResolver\PHPStan\Scope\PHPStanNodeScopeResolver;
use Rector\PhpParser\NodeTraverser\FileWithoutNamespaceNodeTraverser;
use Rector\PhpParser\NodeVisitor\FilePathNodeVisitor;

final class NodeScopeAndMetadataDecorator
{
    private readonly NodeTraverser $nodeTraverser;

    public function __construct(
        CloningVisitor $cloningVisitor,
        private readonly PHPStanNodeScopeResolver $phpStanNodeScopeResolver,
        private readonly FileWithoutNamespaceNodeTraverser $fileWithoutNamespaceNodeTraverser
    ) {
        $this->nodeTraverser = new NodeTraverser();

        // needed for format preserving printing
        $this->nodeTraverser->addVisitor($cloningVisitor);
    }

    /**
     * @param Stmt[] $stmts
     * @return Stmt[]
     */
    public function decorateNodesFromFile(string $filePath, array $stmts): array
    {
        $stmts = $this->fileWithoutNamespaceNodeTraverser->traverse($stmts);
        $stmts = $this->phpStanNodeScopeResolver->processNodes($stmts, $filePath);

        $this->nodeTraverser->addVisitor(new FilePathNodeVisitor($filePath));

        return $this->nodeTraverser->traverse($stmts);
    }
}
