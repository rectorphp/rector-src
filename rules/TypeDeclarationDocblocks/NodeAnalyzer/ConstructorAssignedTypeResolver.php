<?php

declare(strict_types=1);

namespace Rector\TypeDeclarationDocblocks\NodeAnalyzer;

use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Type\Type;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\ValueObject\MethodName;

final readonly class ConstructorAssignedTypeResolver
{
    public function __construct(
        private BetterNodeFinder $betterNodeFinder,
        private NodeNameResolver $nodeNameResolver,
        private NodeTypeResolver $nodeTypeResolver
    ) {
    }

    public function resolve(Class_ $class, string $propertyName): ?Type
    {
        $constructorClassMethod = $class->getMethod(MethodName::CONSTRUCT);
        if (! $constructorClassMethod instanceof ClassMethod) {
            return null;
        }

        if ($constructorClassMethod->getStmts() === []) {
            return null;
        }

        $assigns = $this->betterNodeFinder->findInstancesOfScoped($constructorClassMethod->getStmts(), Assign::class);
        foreach ($assigns as $assign) {
            if (! $assign->var instanceof PropertyFetch) {
                continue;
            }

            $propertyFetch = $assign->var;
            if (! $this->nodeNameResolver-> isName($propertyFetch->var, 'this')) {
                continue;
            }

            if (! $this->nodeNameResolver-> isName($propertyFetch->name, $propertyName)) {
                continue;
            }

            return $this->nodeTypeResolver->getType($assign->expr);
        }

        return null;
    }
}
