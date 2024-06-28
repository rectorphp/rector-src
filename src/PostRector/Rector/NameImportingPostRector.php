<?php

declare(strict_types=1);

namespace Rector\PostRector\Rector;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\InlineHTML;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\NodeTraverser;
use Rector\Application\Provider\CurrentFileProvider;
use Rector\CodingStyle\ClassNameImport\ClassNameImportSkipper;
use Rector\CodingStyle\Node\NameImporter;
use Rector\Exception\ShouldNotHappenException;
use Rector\Naming\Naming\AliasNameResolver;
use Rector\Naming\Naming\UseImportsResolver;
use Rector\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\ValueObject\Application\File;

final class NameImportingPostRector extends AbstractPostRector
{
    public function __construct(
        private readonly NameImporter $nameImporter,
        private readonly ClassNameImportSkipper $classNameImportSkipper,
        private readonly CurrentFileProvider $currentFileProvider,
        private readonly UseImportsResolver $useImportsResolver,
        private readonly AliasNameResolver $aliasNameResolver,
    ) {
    }

    // @todo use refactorWithFile() with use of File directly
    public function enterNode(Node $node): Node|int|null
    {
        if (! $node instanceof FullyQualified) {
            return null;
        }

        $file = $this->currentFileProvider->getFile();
        if (! $file instanceof File) {
            throw new ShouldNotHappenException();
        }

        if ($this->shouldSkipFileWithoutNamespace($file)) {
            return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
        }

        return $this->processNodeName($node, $file);
    }

    private function shouldSkipFileWithoutNamespace(File $file): bool
    {
        $firstStmt = current($file->getNewStmts());
        if (! $firstStmt instanceof FileWithoutNamespace) {
            return false;
        }

        $currentStmt = current($firstStmt->stmts);
        return $currentStmt instanceof InlineHTML || $currentStmt === false;
    }

    private function processNodeName(FullyQualified $fullyQualified, File $file): ?Node
    {
        if ($fullyQualified->isSpecialClassName()) {
            return null;
        }

        $namespaces = array_filter(
            $file->getNewStmts(),
            static fn (Stmt $stmt): bool => $stmt instanceof Namespace_
        );

        if (count($namespaces) > 1) {
            return null;
        }

        /** @var Use_[]|GroupUse[] $currentUses */
        $currentUses = $this->useImportsResolver->resolve();
        if ($this->classNameImportSkipper->shouldSkipName($fullyQualified, $currentUses)) {
            return null;
        }

        $nameInUse = $this->resolveNameInUse($fullyQualified, $currentUses);
        if ($nameInUse instanceof Name) {
            return $nameInUse;
        }

        return $this->nameImporter->importName($fullyQualified, $file);
    }

    /**
     * @param Use_[]|GroupUse[] $currentUses
     */
    private function resolveNameInUse(FullyQualified $fullyQualified, array $currentUses): null|Name
    {
        $aliasName = $this->aliasNameResolver->resolveByName($fullyQualified, $currentUses);
        if (is_string($aliasName)) {
            return new Name($aliasName);
        }

        return $this->resolveLongNameInUseName($fullyQualified, $currentUses);
    }

    /**
     * @param Use_[]|GroupUse[] $currentUses
     */
    private function resolveLongNameInUseName(FullyQualified $fullyQualified, array $currentUses): ?Name
    {
        if (substr_count($fullyQualified->toCodeString(), '\\') === 1) {
            return null;
        }

        $lastName = $fullyQualified->getLast();
        foreach ($currentUses as $currentUse) {
            foreach ($currentUse->uses as $useUse) {
                if ($useUse->name->getLast() !== $lastName) {
                    continue;
                }

                if ($useUse->alias instanceof Identifier && $useUse->alias->toString() !== $lastName) {
                    return new Name($lastName);
                }
            }
        }

        return null;
    }
}
