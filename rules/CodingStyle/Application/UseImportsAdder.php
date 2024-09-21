<?php

declare(strict_types=1);

namespace Rector\CodingStyle\Application;

use Nette\Utils\Strings;
use PhpParser\Comment;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Declare_;
use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Nop;
use PhpParser\Node\Stmt\Use_;
use PHPStan\Type\ObjectType;
use Rector\CodingStyle\ClassNameImport\UsedImportsResolver;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PHPStan\Type\TypeFactory;
use Rector\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\StaticTypeMapper\ValueObject\Type\AliasedObjectType;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;

final readonly class UseImportsAdder
{
    public function __construct(
        private UsedImportsResolver $usedImportsResolver,
        private TypeFactory $typeFactory
    ) {
    }

    /**
     * @param Stmt[] $stmts
     * @param array<FullyQualifiedObjectType|AliasedObjectType> $useImportTypes
     * @param array<FullyQualifiedObjectType|AliasedObjectType> $functionUseImportTypes
     * @param array<FullyQualifiedObjectType|AliasedObjectType> $constantUseImportTypes
     * @return Stmt[]
     */
    public function addImportsToStmts(
        FileWithoutNamespace $fileWithoutNamespace,
        array $stmts,
        array $useImportTypes,
        array $functionUseImportTypes,
        array $constantUseImportTypes
    ): array {
        $usedImports = $this->usedImportsResolver->resolveForStmts($stmts);
        $existingUseImportTypes = $usedImports->getUseImports();
        $existingFunctionUseImportTypes = $usedImports->getFunctionImports();
        $existingConstantUseImportTypes = $usedImports->getConstantImports();

        $newUseImportTypes = $this->diffFullyQualifiedObjectTypes($useImportTypes, $existingUseImportTypes);
        $newFunctionUseImportTypes = $this->diffFullyQualifiedObjectTypes(
            $functionUseImportTypes,
            $existingFunctionUseImportTypes
        );
        $newConstantUseImportTypes = $this->diffFullyQualifiedObjectTypes(
            $constantUseImportTypes,
            $existingConstantUseImportTypes
        );

        $newUses = $this->createUses($newUseImportTypes, $newFunctionUseImportTypes, $newConstantUseImportTypes, null);
        if ($newUses === []) {
            return [$fileWithoutNamespace];
        }

        // place after declare strict_types
        foreach ($stmts as $key => $stmt) {
            // maybe just added a space
            if ($stmt instanceof Nop) {
                continue;
            }

            // when we found a non-declare, directly stop
            if (! $stmt instanceof Declare_) {
                break;
            }

            $position = $key + 1;

            $useComments = $this->getAndRemoveExistingUseComments($stmts, $position);

            // remove space before next use tweak
            if (isset($stmts[$position]) && ($stmts[$position] instanceof Use_ || $stmts[$position] instanceof GroupUse)) {
                $stmts[$position]->setAttribute(AttributeKey::ORIGINAL_NODE, null);
            }

            array_splice($stmts, $position, 0, [new Nop()]);
            ++$position;

            $fileWithoutNamespace->stmts = $this->insertUseNodesInStatements($stmts, $newUses, $position, false);

            if ($useComments !== []) {
                $this->reapplyUseComments($useComments, $fileWithoutNamespace->stmts, $position);
            }

            return [$fileWithoutNamespace];
        }

        $useComments = $this->getAndRemoveExistingUseComments($stmts);

        $fileWithoutNamespace->stmts = $this->insertUseNodesInStatements($fileWithoutNamespace->stmts, $newUses);

        if ($useComments !== []) {
            $this->reapplyUseComments($useComments, $fileWithoutNamespace->stmts);
        }

        return [$fileWithoutNamespace];
    }

    /**
     * @param FullyQualifiedObjectType[] $useImportTypes
     * @param FullyQualifiedObjectType[] $functionUseImportTypes
     * @param FullyQualifiedObjectType[] $constantUseImportTypes
     */
    public function addImportsToNamespace(
        Namespace_ $namespace,
        array $useImportTypes,
        array $functionUseImportTypes,
        array $constantUseImportTypes
    ): void {
        $namespaceName = $this->getNamespaceName($namespace);

        $existingUsedImports = $this->usedImportsResolver->resolveForStmts($namespace->stmts);
        $existingUseImportTypes = $existingUsedImports->getUseImports();
        $existingConstantUseImportTypes = $existingUsedImports->getConstantImports();
        $existingFunctionUseImportTypes = $existingUsedImports->getFunctionImports();

        $existingUseImportTypes = $this->typeFactory->uniquateTypes($existingUseImportTypes);

        $newUseImportTypes = $this->diffFullyQualifiedObjectTypes($useImportTypes, $existingUseImportTypes);

        $newFunctionUseImportTypes = $this->diffFullyQualifiedObjectTypes(
            $functionUseImportTypes,
            $existingFunctionUseImportTypes
        );

        $newConstantUseImportTypes = $this->diffFullyQualifiedObjectTypes(
            $constantUseImportTypes,
            $existingConstantUseImportTypes
        );

        $newUses = $this->createUses(
            $newUseImportTypes,
            $newFunctionUseImportTypes,
            $newConstantUseImportTypes,
            $namespaceName
        );

        if ($newUses === []) {
            return;
        }

        $useComments = $this->getAndRemoveExistingUseComments($namespace->stmts);

        $namespace->stmts = $this->insertUseNodesInStatements($namespace->stmts, $newUses);

        if ($useComments !== []) {
            $this->reapplyUseComments($useComments, $namespace->stmts);
        }
    }

    /**
     * @param Stmt[] $stmts
     * @return Nop[]
     */
    private function resolveInsertNop(array $stmts, int $position): array
    {
        $currentStmt = $stmts[$position] ?? null;
        if (! $currentStmt instanceof Stmt || $currentStmt instanceof Use_ || $currentStmt instanceof GroupUse) {
            return [];
        }

        return [new Nop()];
    }

    /**
     * @param Stmt[] $stmts
     * @return Comment[]
     */
    private function getAndRemoveExistingUseComments(array $stmts, int $indexStmt = 0): array
    {
        $comments = [];
        if ($stmts === []) {
            return $comments;
        }

        if (isset($stmts[$indexStmt]) && $stmts[$indexStmt] instanceof Use_) {
            $comments = (array) $stmts[$indexStmt]->getAttribute(AttributeKey::COMMENTS);

            if ($comments !== []) {
                $stmts[$indexStmt]->setAttribute(AttributeKey::COMMENTS, []);
            }
        }

        return $comments;
    }

    /**
     * @param Comment[] $comments
     * @param Stmt[] $stmts
     */
    private function reapplyUseComments(array $comments, array $stmts, int $indexStmt = 0): void
    {
        if (isset($stmts[$indexStmt])) {
            $stmts[$indexStmt]->setAttribute(AttributeKey::COMMENTS, $comments);
        }
    }

    /**
     * @param array<FullyQualifiedObjectType|AliasedObjectType> $mainTypes
     * @param array<FullyQualifiedObjectType|AliasedObjectType> $typesToRemove
     * @return array<FullyQualifiedObjectType|AliasedObjectType>
     */
    private function diffFullyQualifiedObjectTypes(array $mainTypes, array $typesToRemove): array
    {
        foreach ($mainTypes as $key => $mainType) {
            foreach ($typesToRemove as $typeToRemove) {
                if ($mainType->equals($typeToRemove)) {
                    unset($mainTypes[$key]);
                }
            }
        }

        return array_values($mainTypes);
    }

    /**
     * @param array<AliasedObjectType|FullyQualifiedObjectType> $useImportTypes
     * @param array<FullyQualifiedObjectType|AliasedObjectType> $functionUseImportTypes
     * @param array<FullyQualifiedObjectType|AliasedObjectType> $constantUseImportTypes
     * @return Use_[]
     */
    private function createUses(
        array $useImportTypes,
        array $functionUseImportTypes,
        array $constantUseImportTypes,
        ?string $namespaceName
    ): array {
        $newUses = [];

        /** @var array<Use_::TYPE_*, array<AliasedObjectType|FullyQualifiedObjectType>> $importsMapping */
        $importsMapping = [
            Use_::TYPE_NORMAL => $useImportTypes,
            Use_::TYPE_FUNCTION => $functionUseImportTypes,
            Use_::TYPE_CONSTANT => $constantUseImportTypes,
        ];

        foreach ($importsMapping as $type => $importTypes) {
            $newUsesPerType = [];
            foreach ($importTypes as $importType) {
                if ($namespaceName !== null && $this->isCurrentNamespace($namespaceName, $importType)) {
                    continue;
                }

                // already imported in previous cycle
                $newUsesPerType[] = $importType->getUseNode($type);
            }

            if (SimpleParameterProvider::provideBoolParameter(Option::IMPORT_INSERT_SORTED)) {
                //sort uses by name in ascending order
                usort($newUsesPerType, function (Use_ $use1, Use_ $use2): int {
                    $name1 = $use1->uses[0]->name->toString();
                    $name2 = $use2->uses[0]->name->toString();
                    return strcmp($name1, $name2);
                });
            }

            $newUses = [...$newUses, ...$newUsesPerType];
        }

        return $newUses;
    }

    private function getNamespaceName(Namespace_ $namespace): ?string
    {
        if (! $namespace->name instanceof Name) {
            return null;
        }

        return $namespace->name->toString();
    }

    private function isCurrentNamespace(string $namespaceName, ObjectType $objectType): bool
    {
        $afterCurrentNamespace = Strings::after($objectType->getClassName(), $namespaceName . '\\');
        if ($afterCurrentNamespace === null) {
            return false;
        }

        return ! \str_contains($afterCurrentNamespace, '\\');
    }

    /**
     * @param Stmt[] $stmts
     * @param Use_[] $newUses
     * @return Stmt[]
     */
    private function insertUseNodesInStatements(
        array $stmts,
        array $newUses,
        int $position = 0,
        bool $addSpace = true
    ): array {
        $importInsertSorted = SimpleParameterProvider::provideBoolParameter(Option::IMPORT_INSERT_SORTED);
        if ($importInsertSorted && isset($stmts[$position])
            && ($stmts[$position] instanceof Use_ || $stmts[$position] instanceof GroupUse)) {
            foreach ($newUses as $newUse) {
                do {
                    $useListPosition = 0;
                    $prefix = '';
                    if (! isset($stmts[$position])
                        || (! $stmts[$position] instanceof Use_ && ! $stmts[$position] instanceof GroupUse)) {
                        break;
                    }

                    if ($stmts[$position] instanceof GroupUse) {
                        $prefix = $stmts[$position]->prefix->toString() . '\\';
                    }

                    $parentUseType = $stmts[$position]->type;
                    $newUseType = $newUse->type;
                    foreach ($stmts[$position]->uses as $use) {
                        $currentUseType = $parentUseType === Use_::TYPE_UNKNOWN ? $use->type : $parentUseType;

                        if ($newUseType < $currentUseType) {
                            break 2;
                        }

                        if ($newUseType === $currentUseType
                            && $prefix . $use->name->toString() > $newUse->uses[0]->name->toString()) {
                            break 2;
                        }

                        ++$useListPosition;
                    }

                    ++$position;
                } while (true);

                if ($useListPosition === 0) {
                    array_splice($stmts, $position, 0, [$newUse]);
                    ++$position;
                } else {
                    assert($stmts[$position] instanceof Use_ || $stmts[$position] instanceof GroupUse);
                    if ($prefix !== '') {
                        $newUse->uses[0]->name =
                            new Name(substr($newUse->uses[0]->name->toString(), strlen($prefix)));
                        $newUse->uses[0]->type = $newUse->type;
                    }

                    array_splice($stmts[$position]->uses, $useListPosition, 0, [$newUse->uses[0]]);
                }
            }
        } else {
            if ($addSpace) {
                $newUses = [...$newUses, ...$this->resolveInsertNop($stmts, $position)];
            }

            array_splice($stmts, $position, 0, $newUses);
        }

        return array_values($stmts);
    }
}
