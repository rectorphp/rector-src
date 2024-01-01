<?php

declare(strict_types=1);

namespace Rector\PostRector\Rector;

use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Namespace_;
use Rector\CodingStyle\Application\UseImportsAdder;
use Rector\Exception\ShouldNotHappenException;
use Rector\NodeTypeResolver\PHPStan\Type\TypeFactory;
use Rector\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\PostRector\Collector\UseNodesToAddCollector;
use Rector\Provider\CurrentFileProvider;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use Rector\ValueObject\Application\File;

final class UseAddingPostRector extends AbstractPostRector
{
    public function __construct(
        private readonly TypeFactory $typeFactory,
        private readonly UseImportsAdder $useImportsAdder,
        private readonly UseNodesToAddCollector $useNodesToAddCollector,
        private readonly CurrentFileProvider $currentFileProvider
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

        $rootNode = null;
        foreach ($nodes as $node) {
            if ($node instanceof FileWithoutNamespace || $node instanceof Namespace_) {
                $rootNode = $node;
                break;
            }
        }

        $file = $this->currentFileProvider->getFile();
        if (! $file instanceof File) {
            throw new ShouldNotHappenException();
        }

        $useImportTypes = $this->useNodesToAddCollector->getObjectImportsByFilePath($file->getFilePath());
        $constantUseImportTypes = $this->useNodesToAddCollector->getConstantImportsByFilePath($file->getFilePath());
        $functionUseImportTypes = $this->useNodesToAddCollector->getFunctionImportsByFilePath($file->getFilePath());

        if ($useImportTypes === [] && $constantUseImportTypes === [] && $functionUseImportTypes === []) {
            return $nodes;
        }

        /** @var FullyQualifiedObjectType[] $useImportTypes */
        $useImportTypes = $this->typeFactory->uniquateTypes($useImportTypes);

        if ($rootNode instanceof FileWithoutNamespace) {
            $nodes = $rootNode->stmts;
        }

        if (! $rootNode instanceof FileWithoutNamespace && ! $rootNode instanceof Namespace_) {
            return $nodes;
        }

        return $this->resolveNodesWithImportedUses(
            $nodes,
            $useImportTypes,
            $constantUseImportTypes,
            $functionUseImportTypes,
            $rootNode
        );
    }

    /**
     * @param Stmt[] $nodes
     * @param FullyQualifiedObjectType[] $useImportTypes
     * @param FullyQualifiedObjectType[] $constantUseImportTypes
     * @param FullyQualifiedObjectType[] $functionUseImportTypes
     * @return Stmt[]
     */
    private function resolveNodesWithImportedUses(
        array $nodes,
        array $useImportTypes,
        array $constantUseImportTypes,
        array $functionUseImportTypes,
        FileWithoutNamespace|Namespace_ $namespace
    ): array {
        // A. has namespace? add under it
        if ($namespace instanceof Namespace_) {
            // then add, to prevent adding + removing false positive of same short use
            $this->useImportsAdder->addImportsToNamespace(
                $namespace,
                $useImportTypes,
                $constantUseImportTypes,
                $functionUseImportTypes
            );

            return $nodes;
        }

        // B. no namespace? add in the top
        $useImportTypes = $this->filterOutNonNamespacedNames($useImportTypes);

        // then add, to prevent adding + removing false positive of same short use
        return $this->useImportsAdder->addImportsToStmts(
            $namespace,
            $nodes,
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
}
