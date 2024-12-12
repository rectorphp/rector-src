<?php

declare(strict_types=1);

namespace Rector\PhpParser\Node\Value;

use PhpParser\ConstExprEvaluationException;
use PhpParser\ConstExprEvaluator;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\InterpolatedStringPart;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\MagicConst\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\ConstantScalarType;
use PHPStan\Type\Type;
use Rector\Enum\ObjectReference;
use Rector\Exception\ShouldNotHappenException;
use Rector\NodeAnalyzer\ConstFetchAnalyzer;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\Reflection\ClassReflectionAnalyzer;
use Rector\Reflection\ReflectionResolver;
use Rector\StaticTypeMapper\Resolver\ClassNameFromObjectTypeResolver;
use TypeError;

/**
 * @see \Rector\Tests\PhpParser\Node\Value\ValueResolverTest
 * @todo make use of constant type of $scope->getType()
 */
final class ValueResolver
{
    private ?ConstExprEvaluator $constExprEvaluator = null;

    public function __construct(
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly NodeTypeResolver $nodeTypeResolver,
        private readonly ConstFetchAnalyzer $constFetchAnalyzer,
        private readonly ReflectionProvider $reflectionProvider,
        private readonly ReflectionResolver $reflectionResolver,
        private readonly ClassReflectionAnalyzer $classReflectionAnalyzer
    ) {
    }

    public function isValue(Expr $expr, mixed $value): bool
    {
        return $this->getValue($expr) === $value;
    }

    public function getValue(Arg|Expr|InterpolatedStringPart $expr, bool $resolvedClassReference = false): mixed
    {
        if ($expr instanceof Arg) {
            $expr = $expr->value;
        }

        if ($expr instanceof Concat) {
            return $this->processConcat($expr, $resolvedClassReference);
        }

        if ($expr instanceof ClassConstFetch && $resolvedClassReference) {
            $class = $this->nodeNameResolver->getName($expr->class);

            if (in_array($class, [ObjectReference::SELF, ObjectReference::STATIC], true)) {
                $classReflection = $this->reflectionResolver->resolveClassReflection($expr);
                if ($classReflection instanceof ClassReflection) {
                    return $classReflection->getName();
                }
            }

            if ($this->nodeNameResolver->isName($expr->name, 'class')) {
                return $class;
            }
        }

        $value = $this->resolveExprValueForConst($expr);

        if ($value !== null) {
            return $value;
        }

        if ($expr instanceof ConstFetch) {
            return $this->nodeNameResolver->getName($expr);
        }

        $nodeStaticType = $this->nodeTypeResolver->getType($expr);
        return $this->resolveConstantType($nodeStaticType);
    }

    /**
     * @api symfony
     * @param mixed[] $expectedValues
     */
    public function isValues(Expr $expr, array $expectedValues): bool
    {
        foreach ($expectedValues as $expectedValue) {
            if ($this->isValue($expr, $expectedValue)) {
                return true;
            }
        }

        return false;
    }

    public function isFalse(Expr $expr): bool
    {
        return $this->constFetchAnalyzer->isFalse($expr);
    }

    public function isTrueOrFalse(Expr $expr): bool
    {
        return $this->constFetchAnalyzer->isTrueOrFalse($expr);
    }

    public function isTrue(Expr $expr): bool
    {
        return $this->constFetchAnalyzer->isTrue($expr);
    }

    public function isNull(Expr $expr): bool
    {
        return $this->constFetchAnalyzer->isNull($expr);
    }

    /**
     * @param Expr[]|null[] $nodes
     * @param mixed[] $expectedValues
     */
    public function areValuesEqual(array $nodes, array $expectedValues): bool
    {
        foreach ($nodes as $i => $node) {
            if (! $node instanceof Expr) {
                return false;
            }

            if (! $this->isValue($node, $expectedValues[$i])) {
                return false;
            }
        }

        return true;
    }

    private function resolveExprValueForConst(Expr|InterpolatedStringPart $expr): mixed
    {
        if ($expr instanceof InterpolatedStringPart) {
            return $expr->value;
        }

        try {
            $constExprEvaluator = $this->getConstExprEvaluator();
            return $constExprEvaluator->evaluateDirectly($expr);
        } catch (ConstExprEvaluationException|TypeError) {
        }

        if ($expr instanceof Class_) {
            $type = $this->nodeTypeResolver->getNativeType($expr);
            if ($type instanceof ConstantStringType) {
                return $type->getValue();
            }
        }

        return null;
    }

    private function processConcat(Concat $concat, bool $resolvedClassReference): string
    {
        return $this->getValue($concat->left, $resolvedClassReference) . $this->getValue(
            $concat->right,
            $resolvedClassReference
        );
    }

    private function getConstExprEvaluator(): ConstExprEvaluator
    {
        if ($this->constExprEvaluator instanceof ConstExprEvaluator) {
            return $this->constExprEvaluator;
        }

        $this->constExprEvaluator = new ConstExprEvaluator(function (Expr $expr) {
            // resolve "SomeClass::SOME_CONST"
            if ($expr instanceof ClassConstFetch && $expr->class instanceof Name) {
                return $this->resolveClassConstFetch($expr);
            }

            throw new ConstExprEvaluationException(sprintf(
                'Expression of type "%s" cannot be evaluated',
                $expr->getType()
            ));
        });

        return $this->constExprEvaluator;
    }

    /**
     * @return mixed[]|null
     */
    private function extractConstantArrayTypeValue(ConstantArrayType $constantArrayType): ?array
    {
        $keys = [];
        foreach ($constantArrayType->getKeyTypes() as $i => $keyType) {
            $keys[$i] = $keyType->getValue();
        }

        $values = [];
        foreach ($constantArrayType->getValueTypes() as $i => $valueType) {
            if ($valueType instanceof ConstantArrayType) {
                $value = $this->extractConstantArrayTypeValue($valueType);
            } elseif ($valueType instanceof ConstantScalarType) {
                $value = $valueType->getValue();
            } elseif (ClassNameFromObjectTypeResolver::resolve($valueType) !== null) {
                continue;
            } else {
                return null;
            }

            $values[$keys[$i]] = $value;
        }

        return $values;
    }

    /**
     * @return string|mixed
     */
    private function resolveClassConstFetch(ClassConstFetch $classConstFetch)
    {
        $class = $this->nodeNameResolver->getName($classConstFetch->class);
        $constant = $this->nodeNameResolver->getName($classConstFetch->name);

        if ($class === null) {
            throw new ShouldNotHappenException();
        }

        if ($constant === null) {
            throw new ShouldNotHappenException();
        }

        if (in_array($class, [ObjectReference::SELF, ObjectReference::STATIC, ObjectReference::PARENT], true)) {
            $class = $this->resolveClassFromSelfStaticParent($classConstFetch, $class);
        }

        if ($constant === 'class') {
            return $class;
        }

        $classConstantReference = $class . '::' . $constant;
        if (defined($classConstantReference)) {
            return constant($classConstantReference);
        }

        if (! $this->reflectionProvider->hasClass($class)) {
            // fallback to constant reference itself, to avoid fatal error
            return $classConstantReference;
        }

        $classReflection = $this->reflectionProvider->getClass($class);

        if (! $classReflection->hasConstant($constant)) {
            // fallback to constant reference itself, to avoid fatal error
            return $classConstantReference;
        }

        if ($classReflection->isEnum()) {
            // fallback to constant reference itself, to avoid fatal error
            return $classConstantReference;
        }

        $classConstantReflection = $classReflection->getConstant($constant);
        $valueExpr = $classConstantReflection->getValueExpr();

        if ($valueExpr instanceof ConstFetch) {
            return $this->resolveExprValueForConst($valueExpr);
        }

        return $this->getValue($valueExpr);
    }

    private function resolveClassFromSelfStaticParent(ClassConstFetch $classConstFetch, string $class): string
    {
        // Scope may be loaded too late, so return empty string early
        // it will be resolved on next traverse
        $scope = $classConstFetch->getAttribute(AttributeKey::SCOPE);
        if (! $scope instanceof Scope) {
            return '';
        }

        $classReflection = $this->reflectionResolver->resolveClassReflection($classConstFetch);
        if (! $classReflection instanceof ClassReflection) {
            throw new ShouldNotHappenException(
                'Complete class parent node for to class const fetch, so "self" or "static" references is resolvable to a class name'
            );
        }

        if ($class !== ObjectReference::PARENT) {
            return $classReflection->getName();
        }

        if (! $classReflection->isClass()) {
            throw new ShouldNotHappenException(
                'Complete class parent node for to class const fetch, so "parent" references is resolvable to lookup parent class'
            );
        }

        // ensure parent class name still resolved even not autoloaded
        $parentClassName = $this->classReflectionAnalyzer->resolveParentClassName($classReflection);
        if ($parentClassName === null) {
            throw new ShouldNotHappenException();
        }

        return $parentClassName;
    }

    private function resolveConstantType(Type $constantType): mixed
    {
        if ($constantType instanceof ConstantArrayType) {
            return $this->extractConstantArrayTypeValue($constantType);
        }

        if ($constantType instanceof ConstantScalarType) {
            return $constantType->getValue();
        }

        return null;
    }
}
