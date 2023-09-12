<?php

declare(strict_types=1);

namespace Rector\CodingStyle\ClassNameImport;

use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\UseUse;
use Rector\CodingStyle\ClassNameImport\ValueObject\UsedImports;
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
     */
    public function resolveForStmts(array $stmts): UsedImports
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

        $usedConstImports = [];
        $usedFunctionImports = [];
        /** @param Use_::TYPE_* $useType */
        $this->useImportsTraverser->traverserStmts($stmts, static function (
            int $useType,
            UseUse $useUse,
            string $name
        ) use (&$usedImports, &$usedFunctionImports, &$usedConstImports): void {
            if ($useType === Use_::TYPE_NORMAL) {
                if ($useUse->alias instanceof Identifier) {
                    $usedImports[] = new AliasedObjectType($useUse->alias->toString(), $name);
                } else {
                    $usedImports[] = new FullyQualifiedObjectType($name);
                }
            }

            if ($useType === Use_::TYPE_FUNCTION) {
                $usedFunctionImports[] = new FullyQualifiedObjectType($name);
            }

            if ($useType === Use_::TYPE_CONSTANT) {
                $usedConstImports[] = new FullyQualifiedObjectType($name);
            }
        });

        return new UsedImports($usedImports, $usedFunctionImports, $usedConstImports);
    }
}
