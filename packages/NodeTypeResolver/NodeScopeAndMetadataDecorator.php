<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver;

use PhpParser\Node\Stmt;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\CloningVisitor;
use Rector\Core\PhpParser\NodeTraverser\FileWithoutNamespaceNodeTraverser;
use Rector\Core\PHPStan\NodeVisitor\UnreachableStatementNodeVisitor;
use Rector\Core\ValueObject\Application\File;
use Rector\NodeTypeResolver\NodeVisitor\FunctionLikeParamArgPositionNodeVisitor;
use Rector\NodeTypeResolver\PHPStan\Scope\PHPStanNodeScopeResolver;
use Rector\NodeTypeResolver\PHPStan\Scope\ScopeFactory;

final class NodeScopeAndMetadataDecorator
{
    private readonly NodeTraverser $nodeTraverser;

    public function __construct(
        CloningVisitor $cloningVisitor,
        private readonly PHPStanNodeScopeResolver $phpStanNodeScopeResolver,
        FunctionLikeParamArgPositionNodeVisitor $functionLikeParamArgPositionNodeVisitor,
        private readonly ScopeFactory $scopeFactory,
        private readonly FileWithoutNamespaceNodeTraverser $fileWithoutNamespaceNodeTraverser
    ) {
        $this->nodeTraverser = new NodeTraverser();

        // needed for format preserving printing
        $this->nodeTraverser->addVisitor($cloningVisitor);
        $this->nodeTraverser->addVisitor($functionLikeParamArgPositionNodeVisitor);
    }

    /**
     * @param Stmt[] $stmts
     * @return Stmt[]
     */
    public function decorateNodesFromFile(File|string $file, array $stmts): array
    {
        $filePath = $file instanceof File ? $file->getFilePath() : $file;
        $stmts = $this->fileWithoutNamespaceNodeTraverser->traverse($stmts);
        $stmts = $this->phpStanNodeScopeResolver->processNodes(
            $stmts,
            $filePath
        );

        if ($this->phpStanNodeScopeResolver->hasUnreachableStatementNode()) {
            $unreachableStatementNodeVisitor = new UnreachableStatementNodeVisitor(
                $this->phpStanNodeScopeResolver,
                $filePath,
                $this->scopeFactory
            );
            $this->nodeTraverser->addVisitor($unreachableStatementNodeVisitor);

            $stmts = $this->nodeTraverser->traverse($stmts);

            /**
             * immediate remove UnreachableStatementNodeVisitor after traverse to avoid
             * re-used in nodeTraverser property in next file
             */
            $this->nodeTraverser->removeVisitor($unreachableStatementNodeVisitor);

            // next file must be init hasUnreachableStatementNode to be false again
            $this->phpStanNodeScopeResolver->resetHasUnreachableStatementNode();

            return $stmts;
        }

        return $this->nodeTraverser->traverse($stmts);
    }
}
