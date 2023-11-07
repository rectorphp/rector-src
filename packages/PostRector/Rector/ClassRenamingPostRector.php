<?php

declare(strict_types=1);

namespace Rector\PostRector\Rector;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Namespace_;
use PHPStan\Analyser\Scope;
use Rector\CodingStyle\Application\UseImportsRemover;
use Rector\Core\Configuration\Option;
use Rector\Core\Configuration\Parameter\SimpleParameterProvider;
use Rector\Core\Configuration\RenamedClassesDataCollector;
use Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Renaming\NodeManipulator\ClassRenamer;

final class ClassRenamingPostRector extends AbstractPostRector
{
    private FileWithoutNamespace|Namespace_|null $rootNode = null;

    public function __construct(
        private readonly ClassRenamer $classRenamer,
        private readonly RenamedClassesDataCollector $renamedClassesDataCollector,
        private readonly UseImportsRemover $useImportsRemover
    ) {
    }

    /**
     * @param Stmt[] $nodes
     * @return Stmt[]
     */
    public function beforeTraverse(array $nodes): array
    {
        // ensure reset early on every run to avoid reuse existing value
        $this->rootNode = null;

        foreach ($nodes as $node) {
            if ($node instanceof FileWithoutNamespace || $node instanceof Namespace_) {
                $this->rootNode = $node;
                break;
            }
        }

        return $nodes;
    }

    public function enterNode(Node $node): ?Node
    {
        // no longer need post rename
        if (! $node instanceof Name) {
            return null;
        }

        $oldToNewClasses = $this->renamedClassesDataCollector->getOldToNewClasses();
        if ($oldToNewClasses === []) {
            return null;
        }

        /** @var Scope|null $scope */
        $scope = $node->getAttribute(AttributeKey::SCOPE);
        $result = $this->classRenamer->renameNode($node, $oldToNewClasses, $scope);

        if (! $result instanceof Name && ! $node instanceof FullyQualified) {
            $phpAttributeName = $node->getAttribute(AttributeKey::PHP_ATTRIBUTE_NAME);
            if (is_string($phpAttributeName)) {
                $result = $this->classRenamer->renameNode(
                    new FullyQualified($phpAttributeName, $node->getAttributes()),
                    $oldToNewClasses,
                    $scope
                );
            }
        }

        if (! SimpleParameterProvider::provideBoolParameter(Option::AUTO_IMPORT_NAMES)) {
            return $result;
        }

        if (! $this->rootNode instanceof FileWithoutNamespace && ! $this->rootNode instanceof Namespace_) {
            return $result;
        }

        $removedUses = $this->renamedClassesDataCollector->getOldClasses();
        $this->rootNode->stmts = $this->useImportsRemover->removeImportsFromStmts($this->rootNode->stmts, $removedUses);

        return $result;
    }
}
