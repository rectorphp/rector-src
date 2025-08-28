<?php

declare(strict_types=1);

namespace Rector\Php85\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Cast\String_ as CastString_;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Scalar\InterpolatedString;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\Native\ExtendedNativeParameterReflection;
use PHPStan\Reflection\ParametersAcceptor;
use PHPStan\Type\ErrorType;
use PHPStan\Type\MixedType;
use PHPStan\Type\NullType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use Rector\NodeAnalyzer\PropertyFetchAnalyzer;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PHPStan\ParametersAcceptorSelectorVariantsWrapper;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Rector\Reflection\ReflectionResolver;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see https://wiki.php.net/rfc/deprecations_php_8_5#deprecate_using_values_null_as_an_array_offset_and_when_calling_array_key_exists
 * @see \Rector\Tests\Php85\Rector\FuncCall\RemoveFinfoBufferContextArgRector\RemoveFinfoBufferContextArgRectorTest
 */
final class ArrayKeyExistsNullToEmptyStringRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly ReflectionResolver $reflectionResolver,
        private readonly PropertyFetchAnalyzer $propertyFetchAnalyzer,
        private readonly ValueResolver $valueResolver
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace null key in array_key_exists with empty string',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
        array_key_exists($key, $array);
        CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
        array_key_exists((string)$key, $array);
        CODE_SAMPLE
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    public function refactor(Node $node): ?Node
    {
        if(!$node instanceof FuncCall){
            return null;
        }
        
        if ($node->isFirstClassCallable()) {
            return null;
        }

        if (! $this->isName($node, 'array_key_exists')) {
            return null;
        }

        $scope = $node->getAttribute(AttributeKey::SCOPE);
        if (! $scope instanceof Scope) {
            return null;
        }

        $args = $node->getArgs();

        $classReflection = $scope->getClassReflection();
        $isTrait = $classReflection instanceof ClassReflection && $classReflection->isTrait();

        $functionReflection = $this->reflectionResolver->resolveFunctionLikeReflectionFromCall($node);
        if (! $functionReflection instanceof FunctionReflection) {
            return null;
        }

        $parametersAcceptor = ParametersAcceptorSelectorVariantsWrapper::select($functionReflection, $node, $scope);
        $isChanged = false;

        $result = $this->processNullToStrictStringOnNodePosition(
            $node,
            $args,
            0,
            $isTrait,
            $scope,
            $parametersAcceptor
        );
        if ($result instanceof Node) {
            $node = $result;
            $isChanged = true;
        }

        if ($isChanged) {
            return $node;
        }

        return null;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::DEPRECATE_NULL_ARG_IN_ARRAY_KEY_EXISTS_FUNCTION;
    }

    /**
     * @param Arg[] $args
     * @param int|string $position
     */
    private function processNullToStrictStringOnNodePosition(
        FuncCall $funcCall,
        array $args,
        $position,
        bool $isTrait,
        Scope $scope,
        ParametersAcceptor $parametersAcceptor
    ): ?FuncCall {
        if (! isset($args[$position])) {
            return null;
        }
        $argValue = $args[$position]->value;
        if ($this->valueResolver->isNull($argValue)) {
            $args[$position]->value = new String_('');
            $funcCall->args = $args;
            return $funcCall;
        }
        if ($this->shouldSkipValue($argValue, $scope, $isTrait)) {
            return null;
        }
        $parameter = $parametersAcceptor->getParameters()[$position] ?? null;
        if ($parameter instanceof ExtendedNativeParameterReflection && $parameter->getType() instanceof UnionType) {
            $parameterType = $parameter->getType();
            if (! $this->isValidUnionType($parameterType)) {
                return null;
            }
        }
        if ($argValue instanceof Ternary && ! $this->shouldSkipValue($argValue->else, $scope, $isTrait)) {
            if ($this->valueResolver->isNull($argValue->else)) {
                $argValue->else = new String_('');
            } else {
                $argValue->else = new CastString_($argValue->else);
            }
            $args[$position]->value = $argValue;
            $funcCall->args = $args;
            return $funcCall;
        }
        $args[$position]->value = new CastString_($argValue);
        $funcCall->args = $args;
        return $funcCall;
    }

    private function shouldSkipValue(Expr $expr, Scope $scope, bool $isTrait): bool
    {
        $type = $this->nodeTypeResolver->getType($expr);
        if ($type->isString()->yes()) {
            return \true;
        }
        $nativeType = $this->nodeTypeResolver->getNativeType($expr);
        if ($nativeType->isString()->yes()) {
            return \true;
        }
        if ($this->shouldSkipType($type)) {
            return \true;
        }
        if ($expr instanceof InterpolatedString) {
            return \true;
        }
        if ($this->isAnErrorType($expr, $nativeType, $scope)) {
            return \true;
        }
        return $this->shouldSkipTrait($expr, $type, $isTrait);
    }

    private function isValidUnionType(Type $type): bool
    {
        if (! $type instanceof UnionType) {
            return \false;
        }
        foreach ($type->getTypes() as $childType) {
            if ($childType->isString()->yes()) {
                continue;
            }
            if ($childType->isInteger()->yes()) {
                continue;
            }
            if ($childType->isNull()->yes()) {
                continue;
            }
            return \false;
        }
        return \true;
    }

    private function shouldSkipType(Type $type): bool
    {
        return ! $type instanceof MixedType && ! $type->isNull()
            ->yes() && ! $this->isValidUnionType($type);
    }

    private function shouldSkipTrait(Expr $expr, Type $type, bool $isTrait): bool
    {
        if (! $type instanceof MixedType) {
            return \false;
        }
        if (! $isTrait) {
            return \false;
        }
        if ($type->isExplicitMixed()) {
            return \false;
        }
        if (! $expr instanceof MethodCall) {
            return $this->propertyFetchAnalyzer->isLocalPropertyFetch($expr);
        }
        return \true;
    }

    private function isAnErrorType(Expr $expr, Type $type, Scope $scope): bool
    {
        if ($type instanceof ErrorType) {
            return \true;
        }
        $parentScope = $scope->getParentScope();
        if ($parentScope instanceof Scope) {
            return $parentScope->getType($expr) instanceof ErrorType;
        }
        return $type instanceof MixedType && ! $type->isExplicitMixed() && $type->getSubtractedType() instanceof NullType;
    }
}
