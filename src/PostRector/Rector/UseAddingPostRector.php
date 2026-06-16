<?php

declare(strict_types=1);

namespace Rector\PostRector\Rector;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\NodeVisitor;
use Rector\CodingStyle\Application\UseImportsAdder;
use Rector\CodingStyle\ClassNameImport\ValueObject\UsedImports;
use Rector\NodeTypeResolver\PHPStan\Type\TypeFactory;
use Rector\PhpParser\Node\FileNode;
use Rector\PostRector\Collector\UseNodesToAddCollector;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;

final class UseAddingPostRector extends AbstractPostRector
{
    public function __construct(
        private readonly TypeFactory $typeFactory,
        private readonly UseImportsAdder $useImportsAdder,
        private readonly UseNodesToAddCollector $useNodesToAddCollector,
    ) {
    }

    /**
     * @param Stmt[] $nodes
     * @return Stmt[]
     */
    public function beforeTraverse(array $nodes): array
    {
        // no nodes → just return
        if ($nodes === []) {
            return $nodes;
        }

        $rootNode = $this->resolveRootNode($nodes);
        if (! $rootNode instanceof FileNode && ! $rootNode instanceof Namespace_) {
            return $nodes;
        }

        // the used imports are resolved once on file parse and stored on the FileNode root
        /** @var FileNode $fileNode */
        $fileNode = $nodes[0];

        $useImportTypes = $this->useNodesToAddCollector->getObjectImportsByFilePath($this->getFile()->getFilePath());
        $constantUseImportTypes = $this->useNodesToAddCollector->getConstantImportsByFilePath(
            $this->getFile()
                ->getFilePath()
        );

        $functionUseImportTypes = $this->useNodesToAddCollector->getFunctionImportsByFilePath(
            $this->getFile()
                ->getFilePath()
        );

        if ($useImportTypes === [] && $constantUseImportTypes === [] && $functionUseImportTypes === []) {
            return $nodes;
        }

        /** @var FullyQualifiedObjectType[] $useImportTypes */
        $useImportTypes = $this->typeFactory->uniquateTypes($useImportTypes);

        if ($this->processStmtsWithImportedUses(
            $fileNode->getUsedImports(),
            $useImportTypes,
            $constantUseImportTypes,
            $functionUseImportTypes,
            $rootNode
        )) {
            $this->addRectorClassWithLine($rootNode);
        }

        return $nodes;
    }

    public function enterNode(Node $node): int
    {
        /**
         * We stop the traversal because all the work has already been done in the beforeTraverse() function
         *
         * Using STOP_TRAVERSAL is usually dangerous as it will stop the processing of all your nodes for all visitors
         * but since the PostFileProcessor is using direct new NodeTraverser() and traverse() for only a single
         * visitor per execution, using stop traversal here is safe,
         * ref https://github.com/rectorphp/rector-src/blob/fc1e742fa4d9861ccdc5933f3b53613b8223438d/src/PostRector/Application/PostFileProcessor.php#L59-L61
         */
        return NodeVisitor::STOP_TRAVERSAL;
    }

    /**
     * @param FullyQualifiedObjectType[] $useImportTypes
     * @param FullyQualifiedObjectType[] $constantUseImportTypes
     * @param FullyQualifiedObjectType[] $functionUseImportTypes
     */
    private function processStmtsWithImportedUses(
        UsedImports $usedImports,
        array $useImportTypes,
        array $constantUseImportTypes,
        array $functionUseImportTypes,
        FileNode|Namespace_ $node
    ): bool {
        // no namespace? add in the top, only namespaced names
        if ($node instanceof FileNode) {
            $useImportTypes = $this->filterOutNonNamespacedNames($useImportTypes);
        }

        // then add, to prevent adding + removing false positive of same short use
        return $this->useImportsAdder->addImportsToStmts(
            $node,
            $usedImports,
            $useImportTypes,
            $constantUseImportTypes,
            $functionUseImportTypes
        );
    }

    /**
     * Prevents
     * @param FullyQualifiedObjectType[] $useImportTypes
     * @return FullyQualifiedObjectType[]
     */
    private function filterOutNonNamespacedNames(array $useImportTypes): array
    {
        $namespacedUseImportTypes = [];

        foreach ($useImportTypes as $useImportType) {
            if (! \str_contains($useImportType->getClassName(), '\\')) {
                continue;
            }

            $namespacedUseImportTypes[] = $useImportType;
        }

        return $namespacedUseImportTypes;
    }

    /**
     * @param Stmt[] $nodes
     */
    private function resolveRootNode(array $nodes): Namespace_|FileNode|null
    {
        if ($nodes === []) {
            return null;
        }

        $firstStmt = $nodes[0];
        if (! $firstStmt instanceof FileNode) {
            return null;
        }

        foreach ($firstStmt->stmts as $stmt) {
            if ($stmt instanceof Namespace_) {
                return $stmt;
            }
        }

        return $firstStmt;
    }
}
