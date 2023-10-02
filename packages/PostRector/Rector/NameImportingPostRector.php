<?php

declare(strict_types=1);

namespace Rector\PostRector\Rector;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\InlineHTML;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\CodingStyle\ClassNameImport\ClassNameImportSkipper;
use Rector\CodingStyle\Node\NameImporter;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\Core\Configuration\Option;
use Rector\Core\Configuration\Parameter\SimpleParameterProvider;
use Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\Core\ValueObject\Application\File;
use Rector\Naming\Naming\AliasNameResolver;
use Rector\Naming\Naming\UseImportsResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PhpDoc\NodeAnalyzer\DocBlockNameImporter;

final class NameImportingPostRector extends AbstractPostRector
{
    public function __construct(
        private readonly NameImporter $nameImporter,
        private readonly DocBlockNameImporter $docBlockNameImporter,
        private readonly ClassNameImportSkipper $classNameImportSkipper,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly CurrentFileProvider $currentFileProvider,
        private readonly UseImportsResolver $useImportsResolver,
        private readonly AliasNameResolver $aliasNameResolver,
        private readonly DocBlockUpdater $docBlockUpdater,
    ) {
    }

    public function enterNode(Node $node): ?Node
    {
        if (! SimpleParameterProvider::provideBoolParameter(Option::AUTO_IMPORT_NAMES)) {
            return null;
        }

        $file = $this->currentFileProvider->getFile();
        if (! $file instanceof File) {
            return null;
        }

        $firstStmt = current($file->getNewStmts());
        if ($firstStmt instanceof FileWithoutNamespace && current($firstStmt->stmts) instanceof InlineHTML) {
            return null;
        }

        if ($node instanceof Name) {
            return $this->processNodeName($node, $file);
        }

        if (! $node instanceof Stmt && ! $node instanceof Param) {
            return null;
        }

        $shouldImportDocBlocks = SimpleParameterProvider::provideBoolParameter(Option::AUTO_IMPORT_DOC_BLOCK_NAMES);
        if (! $shouldImportDocBlocks) {
            return null;
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNode($node);
        if (! $phpDocInfo instanceof PhpDocInfo) {
            return null;
        }

        $hasDocChanged = $this->docBlockNameImporter->importNames($phpDocInfo->getPhpDocNode(), $node);
        if (! $hasDocChanged) {
            return null;
        }

        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);
        return $node;
    }

    private function processNodeName(Name $name, File $file): ?Node
    {
        if ($name->isSpecialClassName()) {
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

        if ($this->classNameImportSkipper->shouldImportName($name, $currentUses)) {
            $nameInUse = $this->resolveNameInUse($name, $currentUses);

            if ($nameInUse instanceof Name) {
                return $nameInUse;
            }

            return $this->nameImporter->importName($name, $file);
        }

        return null;
    }

    /**
     * @param Use_[]|GroupUse[] $currentUses
     */
    private function resolveNameInUse(Name $name, array $currentUses): null|Name
    {
        $originalName = $name->getAttribute(AttributeKey::ORIGINAL_NAME);

        if (! $originalName instanceof FullyQualified) {
            return null;
        }

        $aliasName = $this->aliasNameResolver->resolveByName($name, $currentUses);
        if (is_string($aliasName)) {
            return new Name($aliasName);
        }

        return $this->resolveLongNameInUseName($name, $currentUses);
    }

    /**
     * @param Use_[]|GroupUse[] $currentUses
     */
    private function resolveLongNameInUseName(Name $name, array $currentUses): ?Name
    {
        if (substr_count($name->toCodeString(), '\\') === 1) {
            return null;
        }

        $lastName = $name->getLast();
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
