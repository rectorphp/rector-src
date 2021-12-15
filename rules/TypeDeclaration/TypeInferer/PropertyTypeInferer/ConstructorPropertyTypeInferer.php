<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\TypeInferer\PropertyTypeInferer;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PhpParser\NodeTraverser;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use PHPStan\Type\NullType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use Rector\Core\NodeAnalyzer\ParamAnalyzer;
use Rector\Core\NodeManipulator\ClassMethodPropertyFetchManipulator;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\ValueObject\MethodName;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\NodeTypeResolver\PHPStan\Type\TypeFactory;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\StaticTypeMapper\ValueObject\Type\AliasedObjectType;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use Symplify\Astral\NodeTraverser\SimpleCallableNodeTraverser;

final class ConstructorPropertyTypeInferer
{
    public function __construct(
        private readonly ClassMethodPropertyFetchManipulator $classMethodPropertyFetchManipulator,
        private readonly ReflectionProvider $reflectionProvider,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
        private readonly TypeFactory $typeFactory,
        private readonly StaticTypeMapper $staticTypeMapper,
        private readonly NodeTypeResolver $nodeTypeResolver,
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly ParamAnalyzer $paramAnalyzer
    ) {
    }

    public function inferProperty(Property $property): ?Type
    {
        $classLike = $this->betterNodeFinder->findParentType($property, ClassLike::class);
        if (! $classLike instanceof ClassLike) {
            return null;
        }

        $classMethod = $classLike->getMethod(MethodName::CONSTRUCT);
        if (! $classMethod instanceof ClassMethod) {
            return null;
        }

        $propertyName = $this->nodeNameResolver->getName($property);

        // 1. direct property = param assign
        $param = $this->classMethodPropertyFetchManipulator->findParamAssignToPropertyName($classMethod, $propertyName);
        if ($param instanceof Param) {
            if ($param->type !== null) {
                return $this->resolveFromParamType($param, $classMethod, $propertyName);
            }

            return null;
        }

        // 2. different assign
        /** @var Expr[] $assignedExprs */
        $assignedExprs = $this->classMethodPropertyFetchManipulator->findAssignsToPropertyName(
            $classMethod,
            $propertyName
        );

        $resolvedTypes = [];
        foreach ($assignedExprs as $assignedExpr) {
            $resolvedTypes[] = $this->nodeTypeResolver->getType($assignedExpr);
        }

        if (count($resolvedTypes) === 0) {
            return null;
        }

        if (count($resolvedTypes) === 1) {
            return $resolvedTypes[0];
        }

        return TypeCombinator::union(...$resolvedTypes);
    }

    private function resolveFromParamType(Param $param, ClassMethod $classMethod, string $propertyName): Type
    {
        $type = $this->resolveParamTypeToPHPStanType($param);
        if ($type instanceof MixedType) {
            return new MixedType();
        }

        $types = [];

        // it's an array - annotation → make type more precise, if possible
        if ($type instanceof ArrayType || $param->variadic) {
            $types[] = $this->getResolveParamStaticTypeAsPHPStanType($classMethod, $propertyName);
        } else {
            $types[] = $type;
        }

        if ($this->isParamNullable($param)) {
            $types[] = new NullType();
        }

        return $this->typeFactory->createMixedPassedOrUnionType($types);
    }

    private function resolveParamTypeToPHPStanType(Param $param): Type
    {
        if ($param->type === null) {
            return new MixedType();
        }

        if ($this->paramAnalyzer->isNullable($param)) {
            /** @var NullableType $type */
            $type = $param->type;

            $types = [];
            $types[] = new NullType();
            $types[] = $this->staticTypeMapper->mapPhpParserNodePHPStanType($type->type);

            return $this->typeFactory->createMixedPassedOrUnionType($types);
        }

        // special case for alias
        if ($param->type instanceof FullyQualified) {
            $type = $this->resolveFullyQualifiedOrAliasedObjectType($param);
            if ($type !== null) {
                return $type;
            }
        }

        return $this->staticTypeMapper->mapPhpParserNodePHPStanType($param->type);
    }

    private function getResolveParamStaticTypeAsPHPStanType(ClassMethod $classMethod, string $propertyName): Type
    {
        $paramStaticType = new ArrayType(new MixedType(), new MixedType());

        $this->simpleCallableNodeTraverser->traverseNodesWithCallable(
            (array) $classMethod->stmts,
            function (Node $node) use ($propertyName, &$paramStaticType): ?int {
                if (! $node instanceof Variable) {
                    return null;
                }

                if (! $this->nodeNameResolver->isName($node, $propertyName)) {
                    return null;
                }

                $paramStaticType = $this->nodeTypeResolver->getType($node);

                return NodeTraverser::STOP_TRAVERSAL;
            }
        );

        return $paramStaticType;
    }

    private function isParamNullable(Param $param): bool
    {
        if ($this->paramAnalyzer->isNullable($param)) {
            return true;
        }

        if ($param->default !== null) {
            $defaultValueStaticType = $this->nodeTypeResolver->getType($param->default);
            if ($defaultValueStaticType instanceof NullType) {
                return true;
            }
        }

        return false;
    }

    private function resolveFullyQualifiedOrAliasedObjectType(Param $param): ?Type
    {
        if ($param->type === null) {
            return null;
        }

        $fullyQualifiedName = $this->nodeNameResolver->getName($param->type);
        if (! is_string($fullyQualifiedName)) {
            return null;
        }

        $originalName = $param->type->getAttribute(AttributeKey::ORIGINAL_NAME);
        if (! $originalName instanceof Name) {
            return null;
        }

        // if the FQN has different ending than the original, it was aliased and we need to return the alias
        if (! \str_ends_with($fullyQualifiedName, '\\' . $originalName->toString())) {
            $className = $originalName->toString();

            if ($this->reflectionProvider->hasClass($className)) {
                return new FullyQualifiedObjectType($className);
            }

            // @note: $fullyQualifiedName is a guess, needs real life test
            return new AliasedObjectType($originalName->toString(), $fullyQualifiedName);
        }

        return null;
    }
}
