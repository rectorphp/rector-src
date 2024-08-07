<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\TypeInferer;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\Yield_;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\BenevolentUnionType;
use PHPStan\Type\MixedType;
use PHPStan\Type\ThisType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeWithClassName;
use PHPStan\Type\UnionType;
use Rector\Enum\ObjectReference;
use Rector\Exception\ShouldNotHappenException;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\Php\PhpVersionProvider;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Reflection\ReflectionResolver;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use Rector\TypeDeclaration\TypeAnalyzer\GenericClassStringTypeNormalizer;
use Rector\TypeDeclaration\TypeInferer\ReturnTypeInferer\ReturnedNodesReturnTypeInfererTypeInferer;
use Rector\TypeDeclaration\TypeNormalizer;
use Rector\ValueObject\PhpVersionFeature;

/**
 * @internal
 */
final readonly class ReturnTypeInferer
{
    public function __construct(
        private TypeNormalizer $typeNormalizer,
        private ReturnedNodesReturnTypeInfererTypeInferer $returnedNodesReturnTypeInfererTypeInferer,
        private GenericClassStringTypeNormalizer $genericClassStringTypeNormalizer,
        private PhpVersionProvider $phpVersionProvider,
        private BetterNodeFinder $betterNodeFinder,
        private ReflectionResolver $reflectionResolver,
        private ReflectionProvider $reflectionProvider,
        private NodeTypeResolver $nodeTypeResolver
    ) {
    }

    public function inferFunctionLike(ClassMethod|Function_|Closure $functionLike): Type
    {
        $isSupportedStaticReturnType = $this->phpVersionProvider->isAtLeastPhpVersion(
            PhpVersionFeature::STATIC_RETURN_TYPE
        );

        $originalType = $this->returnedNodesReturnTypeInfererTypeInferer->inferFunctionLike($functionLike);
        if ($originalType instanceof MixedType) {
            return new MixedType();
        }

        $type = $this->typeNormalizer->normalizeArrayTypeAndArrayNever($originalType);

        // in case of void, check return type of children methods
        if ($type instanceof MixedType) {
            return new MixedType();
        }

        $type = $this->verifyStaticType($type, $isSupportedStaticReturnType);
        if (! $type instanceof Type) {
            return new MixedType();
        }

        $type = $this->verifyThisType($type, $functionLike);

        // normalize ConstStringType to ClassStringType
        $resolvedType = $this->genericClassStringTypeNormalizer->normalize($type);
        return $this->resolveTypeWithVoidHandling($functionLike, $resolvedType);
    }

    private function verifyStaticType(Type $type, bool $isSupportedStaticReturnType): ?Type
    {
        if ($this->isStaticType($type)) {
            /** @var TypeWithClassName $type */
            return $this->resolveStaticType($isSupportedStaticReturnType, $type);
        }

        if ($type instanceof UnionType) {
            return $this->resolveUnionStaticTypes($type, $isSupportedStaticReturnType);
        }

        return $type;
    }

    private function verifyThisType(Type $type, FunctionLike $functionLike): Type
    {
        if (! $type instanceof ThisType) {
            return $type;
        }

        $classReflection = $this->reflectionResolver->resolveClassReflection($functionLike);
        $objectType = $type->getStaticObjectType();
        $objectTypeClassName = $objectType->getClassName();

        if (! $classReflection instanceof ClassReflection || ! $classReflection->isClass()) {
            return $type;
        }

        if ($classReflection->getName() === $objectTypeClassName) {
            return $type;
        }

        return new MixedType();
    }

    private function resolveTypeWithVoidHandling(
        ClassMethod|Function_|Closure $functionLike,
        Type $resolvedType
    ): Type {
        if ($resolvedType->isVoid()->yes()) {
            $hasReturnValue = (bool) $this->betterNodeFinder->findFirstInFunctionLikeScoped(
                $functionLike,
                static function (Node $subNode): bool {
                    if (! $subNode instanceof Return_) {
                        // yield return is handled on speicific rule: AddReturnTypeDeclarationFromYieldsRector
                        return $subNode instanceof Yield_;
                    }

                    return $subNode->expr instanceof Expr;
                }
            );

            if ($hasReturnValue) {
                return new MixedType();
            }
        }

        if ($resolvedType instanceof UnionType) {
            $benevolentUnionTypeIntegerType = $this->resolveBenevolentUnionTypeInteger($functionLike, $resolvedType);
            if ($benevolentUnionTypeIntegerType->isInteger()->yes()) {
                return $benevolentUnionTypeIntegerType;
            }
        }

        return $resolvedType;
    }

    private function resolveBenevolentUnionTypeInteger(
        ClassMethod|Function_|Closure $functionLike,
        UnionType $unionType
    ): Type {
        $types = $unionType->getTypes();
        $countTypes = count($types);

        if ($countTypes !== 2) {
            return $unionType;
        }

        if (! ($types[0]->isInteger()->yes() && $types[1]->isString()->yes())) {
            return $unionType;
        }

        $returns = $this->betterNodeFinder->findReturnsScoped($functionLike);
        $returnsWithExpr = array_filter(
            $returns,
            static fn (Return_ $return): bool => $return->expr instanceof Expr
        );

        if ($returns !== $returnsWithExpr) {
            return $unionType;
        }

        if ($returnsWithExpr === []) {
            return $unionType;
        }

        foreach ($returnsWithExpr as $returnWithExpr) {
            /** @var Expr $expr */
            $expr = $returnWithExpr->expr;
            $type = $this->nodeTypeResolver->getNativeType($expr);

            if (! $type instanceof BenevolentUnionType) {
                return $unionType;
            }
        }

        return $types[0];
    }

    private function isStaticType(Type $type): bool
    {
        if (! $type instanceof TypeWithClassName) {
            return false;
        }

        return $type->getClassName() === ObjectReference::STATIC;
    }

    private function resolveUnionStaticTypes(UnionType $unionType, bool $isSupportedStaticReturnType): UnionType|null
    {
        $resolvedTypes = [];
        $hasStatic = false;

        foreach ($unionType->getTypes() as $unionedType) {
            if ($this->isStaticType($unionedType)) {
                /** @var FullyQualifiedObjectType $unionedType */
                $classReflection = $this->reflectionProvider->getClass($unionedType->getClassName());

                $resolvedTypes[] = new ThisType($classReflection);
                $hasStatic = true;
                continue;
            }

            $resolvedTypes[] = $unionedType;
        }

        if (! $hasStatic) {
            return $unionType;
        }

        // has static, but it is not supported
        if (! $isSupportedStaticReturnType) {
            return null;
        }

        return new UnionType($resolvedTypes);
    }

    private function resolveStaticType(
        bool $isSupportedStaticReturnType,
        TypeWithClassName $typeWithClassName
    ): ?ThisType {
        if (! $isSupportedStaticReturnType) {
            return null;
        }

        $classReflection = $typeWithClassName->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            throw new ShouldNotHappenException();
        }

        return new ThisType($classReflection);
    }
}
