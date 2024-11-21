<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver;

use PhpParser\Node\Stmt;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\CloningVisitor;
use PHPStan\Parser\ParserErrorsException;
use PHPStan\PhpDocParser\Parser\ParserException;
use Rector\NodeTypeResolver\PHPStan\Scope\PHPStanNodeScopeResolver;
use Rector\NodeTypeResolver\PHPStan\Scope\RectorNodeScopeResolver;
use Rector\NodeTypeResolver\PHPStan\Scope\ScopeFactory;
use Rector\PhpParser\NodeTraverser\FileWithoutNamespaceNodeTraverser;

final readonly class NodeScopeAndMetadataDecorator
{
    private NodeTraverser $nodeTraverser;

    public function __construct(
        CloningVisitor $cloningVisitor,
        private PHPStanNodeScopeResolver $phpStanNodeScopeResolver,
        private FileWithoutNamespaceNodeTraverser $fileWithoutNamespaceNodeTraverser,
        private ScopeFactory $scopeFactory
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

        try {
            $stmts = $this->phpStanNodeScopeResolver->processNodes($stmts, $filePath);
        } catch (ParserErrorsException|ParserException) {
            // nothing we can do more precise here as error parsing from deep internal PHPStan service with service injection we cannot reset
            // in the middle of process
            // fallback to fill by found scope
            RectorNodeScopeResolver::processNodes($stmts, $this->scopeFactory->createFromFile($filePath));
        }

        return $this->nodeTraverser->traverse($stmts);
    }
}
