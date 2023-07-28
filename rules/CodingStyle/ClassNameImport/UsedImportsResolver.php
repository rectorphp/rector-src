<?php

declare(strict_types=1);

namespace Rector\CodingStyle\ClassNameImport;

use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\UseUse;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\StaticTypeMapper\ValueObject\Type\AliasedObjectType;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;

final class UsedImportsResolver
{
    public function __construct(
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly UseImportsTraverser $useImportsTraverser,
        private readonly NodeNameResolver $nodeNameResolver
    ) {
    }

    /**
     * @param Stmt[] $stmts
     * @return array<FullyQualifiedObjectType|AliasedObjectType>
     */
    public function resolveForStmts(array $stmts): array
    {
        $usedImports = [];

        /** @var Class_|null $class */
        $class = $this->betterNodeFinder->findFirstInstanceOf($stmts, Class_::class);

        // add class itself
        // is not anonymous class
        if ($class instanceof Class_) {
            $className = (string) $this->nodeNameResolver->getName($class);
            $usedImports[] = new FullyQualifiedObjectType($className);
        }

        $this->useImportsTraverser->traverserStmts($stmts, static function (
            UseUse $useUse,
            string $name
        ) use (&$usedImports): void {
            if ($useUse->alias instanceof Identifier) {
                $usedImports[] = new AliasedObjectType($useUse->alias->toString(), $name);
            } else {
                $usedImports[] = new FullyQualifiedObjectType($name);
            }
        });

        return $usedImports;
    }

    /**
     * @param Stmt[] $stmts
     * @return FullyQualifiedObjectType[]
     */
    public function resolveConstantImportsForStmts(array $stmts): array
    {
        $usedConstImports = [];

        $this->useImportsTraverser->traverserStmtsForConstants($stmts, static function (
            UseUse $useUse,
            string $name
        ) use (&$usedConstImports): void {
            $usedFunctionImports[] = new FullyQualifiedObjectType($name);
        });

        return $usedFunctionImports;
    }

    /**
     * @param Stmt[] $stmts
     * @return FullyQualifiedObjectType[]
     */
    public function resolveFunctionImportsForStmts(array $stmts): array
    {
        $usedFunctionImports = [];

        $this->useImportsTraverser->traverserStmtsForFunctions($stmts, static function (
            UseUse $useUse,
            string $name
        ) use (&$usedFunctionImports): void {
            $usedFunctionImports[] = new FullyQualifiedObjectType($name);
        });

        return $usedFunctionImports;
    }
}
