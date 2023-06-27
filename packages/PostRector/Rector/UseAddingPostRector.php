<?php

declare(strict_types=1);

namespace Rector\PostRector\Rector;

use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Namespace_;
use Rector\CodingStyle\Application\UseImportsAdder;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\Core\ValueObject\Application\File;
use Rector\NodeTypeResolver\PHPStan\Type\TypeFactory;
use Rector\PostRector\Collector\UseNodesToAddCollector;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

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
        $functionUseImportTypes = $this->useNodesToAddCollector->getFunctionImportsByFilePath($file->getFilePath());

        if ($useImportTypes === [] && $functionUseImportTypes === []) {
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

        return $this->resolveNodesWithImportedUses($nodes, $useImportTypes, $functionUseImportTypes, $rootNode);
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add unique use imports collected during Rector run',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run(AnotherClass $anotherClass)
    {
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use App\AnotherClass;

class SomeClass
{
    public function run(AnotherClass $anotherClass)
    {
    }
}
CODE_SAMPLE
                ), ]
        );
    }

    /**
     * @param Stmt[] $nodes
     * @param FullyQualifiedObjectType[] $useImportTypes
     * @param FullyQualifiedObjectType[] $functionUseImportTypes
     * @return Stmt[]
     */
    private function resolveNodesWithImportedUses(
        array $nodes,
        array $useImportTypes,
        array $functionUseImportTypes,
        FileWithoutNamespace|Namespace_ $namespace
    ): array {
        // A. has namespace? add under it
        if ($namespace instanceof Namespace_) {
            // then add, to prevent adding + removing false positive of same short use
            $this->useImportsAdder->addImportsToNamespace($namespace, $useImportTypes, $functionUseImportTypes);

            return $nodes;
        }

        // B. no namespace? add in the top
        $useImportTypes = $this->filterOutNonNamespacedNames($useImportTypes);

        // then add, to prevent adding + removing false positive of same short use
        return $this->useImportsAdder->addImportsToStmts($namespace, $nodes, $useImportTypes, $functionUseImportTypes);
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
