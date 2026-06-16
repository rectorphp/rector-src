<?php

declare(strict_types=1);

namespace Rector\CodingStyle\Application;

use PhpParser\Node\Name;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Declare_;
use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Nop;
use PhpParser\Node\Stmt\Use_;
use Rector\CodingStyle\ClassNameImport\ValueObject\UsedImports;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PHPStan\Type\TypeFactory;
use Rector\PhpParser\Node\FileNode;
use Rector\StaticTypeMapper\ValueObject\Type\AliasedObjectType;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;

final readonly class UseImportsAdder
{
    public function __construct(
        private TypeFactory $typeFactory
    ) {
    }

    /**
     * @param array<FullyQualifiedObjectType|AliasedObjectType> $useImportTypes
     * @param array<FullyQualifiedObjectType|AliasedObjectType> $constantUseImportTypes
     * @param array<FullyQualifiedObjectType|AliasedObjectType> $functionUseImportTypes
     */
    public function addImportsToStmts(
        FileNode|Namespace_ $node,
        UsedImports $usedImports,
        array $useImportTypes,
        array $constantUseImportTypes,
        array $functionUseImportTypes
    ): bool {
        $namespaceName = $node instanceof Namespace_ ? $this->getNamespaceName($node) : null;

        $existingUseImportTypes = $this->typeFactory->uniquateTypes($usedImports->getUseImports());

        $useImportTypes = $this->diffFullyQualifiedObjectTypes($useImportTypes, $existingUseImportTypes);

        $constantUseImportTypes = $this->diffFullyQualifiedObjectTypes(
            $constantUseImportTypes,
            $usedImports->getConstantImports()
        );

        $functionUseImportTypes = $this->diffFullyQualifiedObjectTypes(
            $functionUseImportTypes,
            $usedImports->getFunctionImports()
        );

        $newUses = $this->createUses($useImportTypes, $constantUseImportTypes, $functionUseImportTypes, $namespaceName);
        if ($newUses === []) {
            return false;
        }

        // remove empty use stmts
        $node->stmts = array_values(array_filter($node->stmts, static function (Stmt $stmt): bool {
            if (! $stmt instanceof Use_) {
                return true;
            }

            return $stmt->uses !== [];
        }));

        // place after declare strict_types
        foreach ($node->stmts as $key => $stmt) {
            // maybe just added a space
            if ($stmt instanceof Nop) {
                continue;
            }

            // when we found a non-declare, directly stop
            if (! $stmt instanceof Declare_) {
                break;
            }

            $nodesToAdd = array_merge([new Nop()], $newUses);

            $this->mirrorUseComments($node->stmts, $newUses, $key + 1);

            // remove space before next use tweak
            if (isset($node->stmts[$key + 1]) && ($node->stmts[$key + 1] instanceof Use_ || $node->stmts[$key + 1] instanceof GroupUse)) {
                $node->stmts[$key + 1]->setAttribute(AttributeKey::ORIGINAL_NODE, null);
            }

            array_splice($node->stmts, $key + 1, 0, $nodesToAdd);

            $node->stmts = array_values($node->stmts);

            return true;
        }

        $this->mirrorUseComments($node->stmts, $newUses);

        // make use stmts first
        $node->stmts = array_merge($newUses, $this->resolveInsertNop($node), $node->stmts);
        $node->stmts = array_values($node->stmts);

        return true;
    }

    /**
     * @return Nop[]
     */
    private function resolveInsertNop(FileNode|Namespace_ $namespace): array
    {
        $currentStmt = $namespace->stmts[0] ?? null;
        if (! $currentStmt instanceof Stmt || $currentStmt instanceof Use_ || $currentStmt instanceof GroupUse) {
            return [];
        }

        return [new Nop()];
    }

    /**
     * @param Stmt[] $stmts
     * @param Use_[] $newUses
     */
    private function mirrorUseComments(array $stmts, array $newUses, int $indexStmt = 0): void
    {
        if ($stmts === []) {
            return;
        }

        if (isset($stmts[$indexStmt]) && $stmts[$indexStmt] instanceof Use_) {
            $comments = (array) $stmts[$indexStmt]->getAttribute(AttributeKey::COMMENTS);

            if ($comments !== []) {
                $newUses[0]->setAttribute(
                    AttributeKey::COMMENTS,
                    $stmts[$indexStmt]->getAttribute(AttributeKey::COMMENTS)
                );

                $stmts[$indexStmt]->setAttribute(AttributeKey::COMMENTS, []);
            }
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
     * @param array<FullyQualifiedObjectType|AliasedObjectType> $constantUseImportTypes
     * @param array<FullyQualifiedObjectType|AliasedObjectType> $functionUseImportTypes
     * @return Use_[]
     */
    private function createUses(
        array $useImportTypes,
        array $constantUseImportTypes,
        array $functionUseImportTypes,
        ?string $namespaceName
    ): array {
        $newUses = [];

        /** @var array<Use_::TYPE_*, array<AliasedObjectType|FullyQualifiedObjectType>> $importsMapping */
        $importsMapping = [
            Use_::TYPE_NORMAL => $useImportTypes,
            Use_::TYPE_CONSTANT => $constantUseImportTypes,
            Use_::TYPE_FUNCTION => $functionUseImportTypes,
        ];

        foreach ($importsMapping as $type => $importTypes) {
            /** @var AliasedObjectType|FullyQualifiedObjectType $importType */
            foreach ($importTypes as $importType) {
                if ($namespaceName !== null && $this->isCurrentNamespace($namespaceName, $importType)) {
                    continue;
                }

                if ($namespaceName === null
                    && $importType instanceof FullyQualifiedObjectType
                    && substr_count(ltrim($importType->getClassName(), '\\'), '\\') === 0) {
                    continue;
                }

                // already imported in previous cycle
                $newUses[] = $importType->getUseNode($type);
            }
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

    private function isCurrentNamespace(
        string $namespaceName,
        AliasedObjectType|FullyQualifiedObjectType $objectType
    ): bool {
        $className = $objectType->getClassName();

        if (! str_starts_with($className, $namespaceName . '\\')) {
            return false;
        }

        return $namespaceName . '\\' . $objectType->getShortName() === $className;
    }
}
