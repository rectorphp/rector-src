<?php

declare(strict_types=1);

namespace Rector\PostRector\Rector;

use PhpParser\Node;
use PhpParser\Node\Stmt\Namespace_;
use PHPStan\Analyser\Scope;
use Rector\CodingStyle\Application\UseImportsRemover;
use Rector\Core\Configuration\RectorConfigProvider;
use Rector\Core\Configuration\RenamedClassesDataCollector;
use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Core\NonPhpFile\Rector\RenameClassNonPhpRector;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PostRector\Contract\Rector\PostRectorDependencyInterface;
use Rector\Renaming\NodeManipulator\ClassRenamer;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ClassRenamingPostRector extends AbstractPostRector implements PostRectorDependencyInterface
{
    public function __construct(
        private readonly ClassRenamer $classRenamer,
        private readonly RenamedClassesDataCollector $renamedClassesDataCollector,
        private readonly RectorConfigProvider $rectorConfigProvider,
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly UseImportsRemover $useImportsRemover
    ) {
    }

    public function getPriority(): int
    {
        // must be run before name importing, so new names are imported
        return 650;
    }

    /**
     * @return class-string<RectorInterface>[]
     */
    public function getRectorDependencies(): array
    {
        return [RenameClassRector::class, RenameClassNonPhpRector::class];
    }

    public function enterNode(Node $node): ?Node
    {
        $oldToNewClasses = $this->renamedClassesDataCollector->getOldToNewClasses();
        if ($oldToNewClasses === []) {
            return null;
        }

        $originalNode = $node->getAttribute(AttributeKey::ORIGINAL_NODE);
        $originalNode ??= $node;

        /** @var Scope|null $scope */
        $scope = $originalNode->getAttribute(AttributeKey::SCOPE);
        $result = $this->classRenamer->renameNode($node, $oldToNewClasses, $scope);

        if (! $this->rectorConfigProvider->shouldImportNames()) {
            return $result;
        }

        $rootNode = $this->betterNodeFinder->findParentByTypes($node, [Namespace_::class, FileWithoutNamespace::class]);
        if (! $rootNode instanceof Node) {
            return $result;
        }

        $removedUses = $this->renamedClassesDataCollector->getOldClasses();
        $this->useImportsRemover->removeImportsFromStmts($rootNode->stmts, $removedUses);

        return $result;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Rename references for classes that were renamed during Rector run', [
            new CodeSample(
                <<<'CODE_SAMPLE'
function (OriginalClass $someClass)
{
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
function (RenamedClass $someClass)
{
}
CODE_SAMPLE
            ),
        ]);
    }
}
