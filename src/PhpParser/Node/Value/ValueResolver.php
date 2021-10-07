<?php

declare(strict_types=1);

namespace Rector\Core\PhpParser\Node\Value;

use PhpParser\ConstExprEvaluationException;
use PhpParser\ConstExprEvaluator;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Scalar\MagicConst\Dir;
use PhpParser\Node\Scalar\MagicConst\File;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\ConstantScalarType;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\NodeAnalyzer\ConstFetchAnalyzer;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\NodeTypeResolver;

/**
 * @see \Rector\Core\Tests\PhpParser\Node\Value\ValueResolverTest
 */
final class ValueResolver
{
    private ?ConstExprEvaluator $constExprEvaluator = null;

    public function __construct(
        private NodeNameResolver $nodeNameResolver,
        private NodeTypeResolver $nodeTypeResolver,
        private ConstFetchAnalyzer $constFetchAnalyzer,
        private ReflectionProvider $reflectionProvider,
        private CurrentFileProvider $currentFileProvider
    ) {
    }

    /**
     * @param mixed $value
     */
    public function isValue(Expr $expr, $value): bool
    {
        return $this->getValue($expr) === $value;
    }

    /**
     * @return mixed|null
     */
    public function getValue(Expr $expr, bool $resolvedClassReference = false)
    {
        if ($expr instanceof Concat) {
            return $this->processConcat($expr, $resolvedClassReference);
        }

        if ($expr instanceof ClassConstFetch && $resolvedClassReference) {
            $class = $this->nodeNameResolver->getName($expr->class);

            if (in_array($class, ['self', 'static'], true)) {
                return $expr->getAttribute(AttributeKey::CLASS_NAME);
            }

            if ($this->nodeNameResolver->isName($expr->name, 'class')) {
                return $class;
            }
        }

        try {
            $constExprEvaluator = $this->getConstExprEvaluator();
            $value = $constExprEvaluator->evaluateDirectly($expr);
        } catch (ConstExprEvaluationException) {
            $value = null;
        }

        if ($value !== null) {
            return $value;
        }

        if ($expr instanceof ConstFetch) {
            return $this->nodeNameResolver->getName($expr);
        }

        $nodeStaticType = $this->nodeTypeResolver->getType($expr);

        if ($nodeStaticType instanceof ConstantArrayType) {
            return $this->extractConstantArrayTypeValue($nodeStaticType);
        }

        if ($nodeStaticType instanceof ConstantScalarType) {
            return $nodeStaticType->getValue();
        }

        return null;
    }

    /**
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

    public function isFalse(Node $node): bool
    {
        return $this->constFetchAnalyzer->isFalse($node);
    }

    public function isTrueOrFalse(Node $node): bool
    {
        return $this->constFetchAnalyzer->isTrueOrFalse($node);
    }

    public function isTrue(Node $node): bool
    {
        return $this->constFetchAnalyzer->isTrue($node);
    }

    public function isNull(Node $node): bool
    {
        return $this->constFetchAnalyzer->isNull($node);
    }

    public function isValueEqual(Expr $firstExpr, Expr $secondExpr): bool
    {
        $firstValue = $this->getValue($firstExpr);
        $secondValue = $this->getValue($secondExpr);

        return $firstValue === $secondValue;
    }

    /**
     * @param Expr[]|null[] $nodes
     * @param mixed[] $expectedValues
     */
    public function areValuesEqual(array $nodes, array $expectedValues): bool
    {
        foreach ($nodes as $i => $node) {
            if ($node === null) {
                return false;
            }

            if (! $this->isValue($node, $expectedValues[$i])) {
                return false;
            }
        }

        return true;
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
        if ($this->constExprEvaluator !== null) {
            return $this->constExprEvaluator;
        }

        $this->constExprEvaluator = new ConstExprEvaluator(function (Expr $expr) {
            if ($expr instanceof Dir) {
                // __DIR__
                return $this->resolveDirConstant();
            }

            if ($expr instanceof File) {
                // __FILE__
                return $this->resolveFileConstant($expr);
            }

            // resolve "SomeClass::SOME_CONST"
            if ($expr instanceof ClassConstFetch) {
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
     * @return mixed[]
     */
    private function extractConstantArrayTypeValue(ConstantArrayType $constantArrayType): array
    {
        $keys = [];
        foreach ($constantArrayType->getKeyTypes() as $i => $keyType) {
            /** @var ConstantScalarType $keyType */
            $keys[$i] = $keyType->getValue();
        }

        $values = [];
        foreach ($constantArrayType->getValueTypes() as $i => $valueType) {
            if ($valueType instanceof ConstantArrayType) {
                $value = $this->extractConstantArrayTypeValue($valueType);
            } elseif ($valueType instanceof ConstantScalarType) {
                $value = $valueType->getValue();
            } else {
                // not sure about value
                continue;
            }

            $values[$keys[$i]] = $value;
        }

        return $values;
    }

    private function resolveDirConstant(): string
    {
        $file = $this->currentFileProvider->getFile();
        $smartFileInfo = $file->getSmartFileInfo();
        return $smartFileInfo->getPath();
    }

    private function resolveFileConstant(File $file): string
    {
        $file = $this->currentFileProvider->getFile();

        $smartFileInfo = $file->getSmartFileInfo();
        return $smartFileInfo->getPathname();
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

        if ($class === 'self') {
            $class = (string) $classConstFetch->class->getAttribute(AttributeKey::CLASS_NAME);
        }

        if ($constant === 'class') {
            return $class;
        }

        $classConstantReference = $class . '::' . $constant;
        if (defined($classConstantReference)) {
            return constant($classConstantReference);
        }

        if ($this->reflectionProvider->hasClass($class)) {
            $classReflection = $this->reflectionProvider->getClass($class);

            if ($classReflection->hasConstant($constant)) {
                $constantReflection = $classReflection->getConstant($constant);
                return $constantReflection->getValue();
            }
        }

        // fallback to constant reference itself, to avoid fatal error
        return $classConstantReference;
    }
}
