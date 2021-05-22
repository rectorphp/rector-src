<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\TypeInferer\PropertyTypeInferer;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Property;
use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\TypeDeclaration\Contract\TypeInferer\PropertyTypeInfererInterface;
use Rector\TypeDeclaration\FunctionLikeReturnTypeResolver;
use Rector\TypeDeclaration\NodeAnalyzer\ClassMethodAndPropertyAnalyzer;

final class GetterTypeDeclarationPropertyTypeInferer implements PropertyTypeInfererInterface
{
    public function __construct(
        private FunctionLikeReturnTypeResolver $functionLikeReturnTypeResolver,
        private ClassMethodAndPropertyAnalyzer $classMethodAndPropertyAnalyzer,
        private NodeNameResolver $nodeNameResolver,
        private BetterNodeFinder $betterNodeFinder
    ) {
    }

    public function inferProperty(Property $property): ?Type
    {
        $classLike = $this->betterNodeFinder->findParentType($property, ClassLike::class);
        if (! $classLike instanceof Class_) {
            // anonymous class
            return null;
        }

        /** @var string $propertyName */
        $propertyName = $this->nodeNameResolver->getName($property);

        foreach ($classLike->getMethods() as $classMethod) {
            if (! $this->classMethodAndPropertyAnalyzer->hasClassMethodOnlyStatementReturnOfPropertyFetch(
                $classMethod,
                $propertyName
            )) {
                continue;
            }

            $returnType = $this->functionLikeReturnTypeResolver->resolveFunctionLikeReturnTypeToPHPStanType(
                $classMethod
            );
            // let PhpDoc solve that later for more precise type
            if ($returnType instanceof ArrayType) {
                return new MixedType();
            }

            if (! $returnType instanceof MixedType) {
                return $returnType;
            }
        }

        return null;
    }

    public function getPriority(): int
    {
        return 630;
    }
}
