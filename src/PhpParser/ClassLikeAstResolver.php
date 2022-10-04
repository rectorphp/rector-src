<?php

declare(strict_types=1);

namespace Rector\Core\PhpParser;

use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Enum_;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Trait_;
use PHPStan\Reflection\ClassReflection;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\PhpDocParser\PhpParser\SmartPhpParser;

final class ClassLikeAstResolver
{
    /**
     * Parsing files is very heavy performance, so this will help to leverage it
     * The value can be also null, as the method might not exist in the class.
     *
     * @var array<class-string, Class_|Trait_|Interface_|Enum_|null>
     */
    private array $classLikesByName = [];

    public function __construct(
        private readonly SmartPhpParser $smartPhpParser,
        private readonly NodeNameResolver $nodeNameResolver
    ) {
    }

    public function resolveClassFromClassReflection(
        ClassReflection $classReflection
    ): Trait_ | Class_ | Interface_ | Enum_ | null {
        if ($classReflection->isBuiltin()) {
            return null;
        }

        $className = $classReflection->getName();
        if (isset($this->classLikesByName[$className])) {
            return $this->classLikesByName[$className];
        }

        $fileName = $classReflection->getFileName();

        // probably internal class
        if ($fileName === null) {
            // avoid parsing falsy-file again
            $this->classLikesByName[$className] = null;
            return null;
        }

        $stmts = $this->smartPhpParser->parseFile($fileName);
        if ($stmts === []) {
            // avoid parsing falsy-file again
            $this->classLikesByName[$className] = null;
            return null;
        }

        return $this->resolveClassLikeFromStmts($stmts, $className);
    }

    /**
     * @param Stmt[] $stmts
     */
    private function resolveClassLikeFromStmts(array $stmts, string $className): ?ClassLike
    {
        $classStmt = null;

        foreach ($stmts as $stmt) {
            if ($stmt instanceof Namespace_) {
                $classLike = $this->resolveClassLikeFromStmts($stmt->stmts, $className);
                if ($classLike instanceof ClassLike) {
                    return $classLike;
                }
            }

            if (! $stmt instanceof ClassLike) {
                continue;
            }

            $this->classLikesByName[$this->nodeNameResolver->getName($stmt)] = $stmt;
            if ($this->nodeNameResolver->isName($stmt, $className)) {
                $classStmt = $stmt;
            }
        }

        $this->classLikesByName[$className] = $classStmt;
        if ($classStmt instanceof ClassLike) {
            return $classStmt;
        }

        return null;
    }
}
