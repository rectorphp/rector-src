<?php

declare(strict_types=1);

namespace Rector\TypeDeclarationDocblocks\TypeResolver;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\Yield_;
use PhpParser\Node\Expr\YieldFrom;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\NodeTypeResolver\PHPStan\Type\TypeFactory;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedGenericObjectType;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;

final readonly class YieldTypeResolver
{
    public function __construct(
        private NodeTypeResolver $nodeTypeResolver,
        private NodeNameResolver $nodeNameResolver,
        private TypeFactory $typeFactory,
    ) {
    }

    /**
     * @param array<Yield_|YieldFrom> $yieldNodes
     */
    public function resolveFromYieldNodes(
        array $yieldNodes,
        ClassMethod|Function_ $functionLike
    ): FullyQualifiedObjectType|FullyQualifiedGenericObjectType {
        $yieldedTypes = $this->resolveYieldedTypes($yieldNodes);

        $className = $this->resolveClassName($functionLike);

        if ($yieldedTypes === []) {
            return new FullyQualifiedObjectType($className);
        }

        $yieldedTypes = $this->typeFactory->createMixedPassedOrUnionType($yieldedTypes);
        return new FullyQualifiedGenericObjectType($className, [$yieldedTypes]);
    }

    private function resolveYieldValue(Yield_ | YieldFrom $yield): ?Expr
    {
        if ($yield instanceof Yield_) {
            return $yield->value;
        }

        return $yield->expr;
    }

    /**
     * @param array<Yield_|YieldFrom> $yieldNodes
     * @return Type[]
     */
    private function resolveYieldedTypes(array $yieldNodes): array
    {
        $yieldedTypes = [];

        foreach ($yieldNodes as $yieldNode) {
            $value = $this->resolveYieldValue($yieldNode);
            if (! $value instanceof Expr) {
                // one of the yields is empty
                return [];
            }

            $resolvedType = $this->nodeTypeResolver->getType($value);
            if ($resolvedType instanceof MixedType) {
                continue;
            }

            $yieldedTypes[] = $resolvedType;
        }

        return $yieldedTypes;
    }

    private function resolveClassName(Function_|ClassMethod|Closure $functionLike): string
    {
        $returnTypeNode = $functionLike->getReturnType();

        if ($returnTypeNode instanceof Identifier && $returnTypeNode->name === 'iterable') {
            return 'Iterator';
        }

        if ($returnTypeNode instanceof Name && ! $this->nodeNameResolver->isName($returnTypeNode, 'Generator')) {
            return $this->nodeNameResolver->getName($returnTypeNode);
        }

        return 'Generator';
    }
}
