<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver;

use PhpParser\Node\Stmt;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\CloningVisitor;
use PhpParser\NodeVisitor\NodeConnectingVisitor;
use Rector\Core\ValueObject\Application\File;
use Rector\NodeTypeResolver\NodeVisitor\FunctionLikeParamArgPositionNodeVisitor;
use Rector\NodeTypeResolver\PHPStan\Scope\PHPStanNodeScopeResolver;

final class NodeScopeAndMetadataDecorator
{
    private readonly NodeTraverser $nodeTraverser;

    public function __construct(
        CloningVisitor $cloningVisitor,
        private readonly PHPStanNodeScopeResolver $phpStanNodeScopeResolver,
        NodeConnectingVisitor $nodeConnectingVisitor,
        FunctionLikeParamArgPositionNodeVisitor $functionLikeParamArgPositionNodeVisitor,
    ) {
        $this->nodeTraverser = new NodeTraverser();

        // needed also for format preserving printing
        $this->nodeTraverser->addVisitor($cloningVisitor);

        // this one has to be run again to re-connect nodes with new attributes
        $this->nodeTraverser->addVisitor($nodeConnectingVisitor);
        $this->nodeTraverser->addVisitor($functionLikeParamArgPositionNodeVisitor);
    }

    /**
     * @param Stmt[] $stmts
     * @return Stmt[]
     */
    public function decorateNodesFromFile(File $file, array $stmts): array
    {
        $stmts = $this->phpStanNodeScopeResolver->processNodes($stmts, $file->getFilePath());
        return $this->nodeTraverser->traverse($stmts);
    }
}
