<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\TypeInferer;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\UnionType as PhpParserUnionType;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\BenevolentUnionType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\MixedType;
use PHPStan\Type\StringType;
use PHPStan\Type\ThisType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeWithClassName;
use PHPStan\Type\UnionType;
use PHPStan\Type\VoidType;
use Rector\Core\Configuration\Option;
use Rector\Core\Enum\ObjectReference;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\Php\PhpVersionProvider;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use Rector\TypeDeclaration\Contract\TypeInferer\ReturnTypeInfererInterface;
use Rector\TypeDeclaration\Sorter\PriorityAwareSorter;
use Rector\TypeDeclaration\TypeAnalyzer\GenericClassStringTypeNormalizer;
use Rector\TypeDeclaration\TypeNormalizer;
use Symplify\PackageBuilder\Parameter\ParameterProvider;

final class ReturnTypeInferer
{
    /**
     * @var ReturnTypeInfererInterface[]
     */
    private array $returnTypeInferers = [];

    /**
     * @param ReturnTypeInfererInterface[] $returnTypeInferers
     */
    public function __construct(
        array $returnTypeInferers,
        private readonly TypeNormalizer $typeNormalizer,
        PriorityAwareSorter $priorityAwareSorter,
        private readonly GenericClassStringTypeNormalizer $genericClassStringTypeNormalizer,
        private readonly PhpVersionProvider $phpVersionProvider,
        private readonly ParameterProvider $parameterProvider,
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly ReflectionProvider $reflectionProvider,
        private readonly NodeTypeResolver $nodeTypeResolver,
        private readonly NodeNameResolver $nodeNameResolver
    ) {
        $this->returnTypeInferers = $priorityAwareSorter->sort($returnTypeInferers);
    }

    public function inferFunctionLike(ClassMethod|Function_|Closure $functionLike): Type
    {
        return $this->inferFunctionLikeWithExcludedInferers($functionLike, []);
    }

    /**
     * @param array<class-string<ReturnTypeInfererInterface>> $excludedInferers
     */
    public function inferFunctionLikeWithExcludedInferers(
        ClassMethod|Function_|Closure $functionLike,
        array $excludedInferers
    ): Type {
        $isSupportedStaticReturnType = $this->phpVersionProvider->isAtLeastPhpVersion(
            PhpVersionFeature::STATIC_RETURN_TYPE
        );

        $isAutoImport = $this->parameterProvider->provideBoolParameter(Option::AUTO_IMPORT_NAMES);
        if ($this->isAutoImportWithFullyQualifiedReturn($isAutoImport, $functionLike)) {
            return new MixedType();
        }

        foreach ($this->returnTypeInferers as $returnTypeInferer) {
            if ($this->shouldSkipExcludedTypeInferer($returnTypeInferer, $excludedInferers)) {
                continue;
            }

            $originalType = $returnTypeInferer->inferFunctionLike($functionLike);
            if ($originalType instanceof MixedType) {
                continue;
            }

            $type = $this->typeNormalizer->normalizeArrayTypeAndArrayNever($originalType);

            // in case of void, check return type of children methods
            if ($type instanceof MixedType) {
                continue;
            }

            $type = $this->verifyStaticType($type, $isSupportedStaticReturnType);
            if (! $type instanceof Type) {
                continue;
            }

            $type = $this->verifyThisType($type, $functionLike);
            if (! $type instanceof Type) {
                continue;
            }

            // normalize ConstStringType to ClassStringType
            $resolvedType = $this->genericClassStringTypeNormalizer->normalize($type);
            return $this->resolveTypeWithVoidHandling($functionLike, $resolvedType);
        }

        return new MixedType();
    }

    public function verifyStaticType(Type $type, bool $isSupportedStaticReturnType): ?Type
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

    public function verifyThisType(Type $type, FunctionLike $functionLike): ?Type
    {
        if (! $type instanceof ThisType) {
            return $type;
        }

        $class = $this->betterNodeFinder->findParentType($functionLike, Class_::class);
        $objectType = $type->getStaticObjectType();
        $objectTypeClassName = $objectType->getClassName();

        if (! $class instanceof Class_) {
            return $type;
        }

        if ($this->nodeNameResolver->isName($class, $objectTypeClassName)) {
            return $type;
        }

        return new MixedType();
    }

    private function resolveTypeWithVoidHandling(ClassMethod|Function_|Closure $functionLike, Type $resolvedType): Type
    {
        if ($resolvedType instanceof VoidType) {
            $hasReturnValue = (bool) $this->betterNodeFinder->findFirstInFunctionLikeScoped(
                $functionLike,
                function (Node $subNode): bool {
                    if (! $subNode instanceof Return_) {
                        return false;
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
            if ($benevolentUnionTypeIntegerType instanceof IntegerType) {
                return $benevolentUnionTypeIntegerType;
            }
        }

        return $resolvedType;
    }

    private function resolveBenevolentUnionTypeInteger(
        ClassMethod|Function_|Closure $functionLike,
        UnionType $unionType
    ): UnionType|IntegerType {
        $types = $unionType->getTypes();
        $countTypes = count($types);

        if ($countTypes !== 2) {
            return $unionType;
        }

        if (! ($types[0] instanceof IntegerType && $types[1] instanceof StringType)) {
            return $unionType;
        }

        $returns = $this->betterNodeFinder->findInstancesOfInFunctionLikeScoped($functionLike, Return_::class);
        $returnsWithExpr = array_filter($returns, fn ($v): bool => $v->expr instanceof Expr);

        if ($returns !== $returnsWithExpr) {
            return $unionType;
        }

        if ($returnsWithExpr === []) {
            return $unionType;
        }

        foreach ($returnsWithExpr as $returnWithExpr) {
            /** @var Expr $expr */
            $expr = $returnWithExpr->expr;
            $type = $this->nodeTypeResolver->getType($expr);

            if (! $type instanceof BenevolentUnionType) {
                return $unionType;
            }
        }

        return $types[0];
    }

    private function isAutoImportWithFullyQualifiedReturn(bool $isAutoImport, FunctionLike $functionLike): bool
    {
        if (! $isAutoImport) {
            return false;
        }

        if (! $functionLike instanceof ClassMethod) {
            return false;
        }

        if ($this->isNamespacedFullyQualified($functionLike->returnType)) {
            return true;
        }

        if (! $functionLike->returnType instanceof PhpParserUnionType) {
            return false;
        }

        $types = $functionLike->returnType->types;
        foreach ($types as $type) {
            if ($this->isNamespacedFullyQualified($type)) {
                return true;
            }
        }

        return false;
    }

    private function isNamespacedFullyQualified(?Node $node): bool
    {
        return $node instanceof FullyQualified && str_contains($node->toString(), '\\');
    }

    private function isStaticType(Type $type): bool
    {
        if (! $type instanceof TypeWithClassName) {
            return false;
        }

        return $type->getClassName() === ObjectReference::STATIC()->getValue();
    }

    /**
     * @param array<class-string<ReturnTypeInfererInterface>> $excludedInferers
     */
    private function shouldSkipExcludedTypeInferer(
        ReturnTypeInfererInterface $returnTypeInferer,
        array $excludedInferers
    ): bool {
        foreach ($excludedInferers as $excludedInferer) {
            if (is_a($returnTypeInferer, $excludedInferer)) {
                return true;
            }
        }

        return false;
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
