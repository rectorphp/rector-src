<?php

declare(strict_types=1);

namespace Rector\Core\PhpParser;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Enum_;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Trait_;
use PHPStan\Reflection\ClassReflection;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\NodeNameResolver\NodeNameResolver;
use Symfony\Contracts\Service\Attribute\Required;

final class ClassLikeAstResolver
{
    private AstResolver $astResolver;

    public function __construct(
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly NodeNameResolver $nodeNameResolver,
    ) {
    }

    #[Required]
    public function autowire(AstResolver $astResolver): void
    {
        $this->astResolver = $astResolver;
    }

    public function resolveClassFromClassReflection(
        ClassReflection $classReflection
    ): Trait_ | Class_ | Interface_ | Enum_ | null {
        if ($classReflection->isBuiltin()) {
            return null;
        }

        $className = $classReflection->getName();
        $fileName = $classReflection->getFileName();

        // probably internal class
        if ($fileName === null) {
            return null;
        }

        $stmts = $this->astResolver->parseFileNameToDecoratedNodes($fileName);
        if ($stmts === []) {
            return null;
        }

        /** @var Class_|Trait_|Interface_|Enum_|null $classLike */
        $classLike = $this->betterNodeFinder->findFirst(
            $stmts,
            function (Node $node) use ($className): bool {
                if (! $node instanceof ClassLike) {
                    return false;
                }

                return $this->nodeNameResolver->isName($node, $className);
            }
        );

        return $classLike;
    }
}
