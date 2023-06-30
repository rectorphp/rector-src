<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver;

use PhpParser\Node\Stmt;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\CloningVisitor;
use PhpParser\NodeVisitor\ParentConnectingVisitor;
use Rector\Core\PhpParser\NodeTraverser\FileWithoutNamespaceNodeTraverser;
use Rector\Core\ValueObject\Application\File;
use Rector\NodeTypeResolver\NodeVisitor\FunctionLikeParamArgPositionNodeVisitor;
use Rector\NodeTypeResolver\PHPStan\Scope\PHPStanNodeScopeResolver;

final class NodeScopeAndMetadataDecorator
{
    private readonly NodeTraverser $nodeTraverser;

    public function __construct(
        CloningVisitor $cloningVisitor,
        private readonly PHPStanNodeScopeResolver $phpStanNodeScopeResolver,
        FunctionLikeParamArgPositionNodeVisitor $functionLikeParamArgPositionNodeVisitor,
        private readonly FileWithoutNamespaceNodeTraverser $fileWithoutNamespaceNodeTraverser
    ) {
        $this->nodeTraverser = new NodeTraverser();

        // needed also for format preserving printing
        $this->nodeTraverser->addVisitor($cloningVisitor);

        // connect parent nodes with new attributes
        $this->nodeTraverser->addVisitor(new ParentConnectingVisitor());

        $this->nodeTraverser->addVisitor($functionLikeParamArgPositionNodeVisitor);
    }

    /**
     * @param Stmt[] $stmts
     * @return Stmt[]
     */
    public function decorateNodesFromFile(File $file, array $stmts): array
    {
        $stmts = $this->fileWithoutNamespaceNodeTraverser->traverse($stmts);
        $stmts = $this->nodeTraverser->traverse($stmts);

        return $this->phpStanNodeScopeResolver->processNodes($stmts, $file->getFilePath());
    }
}
