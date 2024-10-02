<?php

namespace Rector\TypeDeclaration\Rector\FunctionLike;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Param;
use PhpParser\Node\VariadicPlaceholder;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\PHPStanStaticTypeMapper\Utils\TypeUnwrapper;
use Rector\Rector\AbstractRector;
use PHPStan\Type\CallableType;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\Type;
use PHPStan\Reflection\ParameterReflection;
use Rector\Reflection\MethodReflectionResolver;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class AddClosureParamTypeFromIterableMethodCallRector extends AbstractRector
{
    public function __construct(
        private readonly TypeComparator $typeComparator,
        private readonly StaticTypeMapper $staticTypeMapper,
        private readonly MethodReflectionResolver $methodReflectionResolver,
        private readonly TypeUnwrapper $typeUnwrapper,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Applies type hints to closures on Iterable method calls where key/value types are documented',
            [
                new CodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @param Collection<int, string> $string
     */
    public function run(Collection $collection)
    {
        return $collection->map(function ($item, $key) {
            return $item . $key;
        });
    }
}
CODE_SAMPLE,
                <<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @param Collection<string> $string
     */
    public function run(Collection $collection)
    {
        return $collection->map(function (string $item, int $key) {
            return $item . $key;
        });
    }
}
CODE_SAMPLE,
)
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [Node\Expr\MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        $varType = $this->getType($node->var);

        if (! $varType instanceof IntersectionType || ! $varType->isIterable()->yes()) {
            return null;
        }

        if ($node->isFirstClassCallable()) {
            return null;
        }

        $className = $varType->getObjectClassNames()[0] ?? null;

        if ($className === null) {
            return null;
        }

        $methodReflection = $this->methodReflectionResolver->resolveMethodReflection(
            $className,
            $node->name,
            $node->getAttribute(AttributeKey::SCOPE),
        );

        $parameters = $methodReflection->getVariants()[0]->getParameters();

        if (! $this->methodSignatureUsesCallableWithIteratorTypes($className, $parameters)) {
            return null;
        }

        if (! $this->callUsesClosures($node->getArgs())) {
            return null;
        }

        $nameIndex = [];
        foreach ($parameters as $index => $parameter) {
            $nameIndex[$parameter->getName()] = $index;
        }

        $valueType = $varType->getIterableValueType();
        $keyType = $varType->getIterableKeyType();

        $changesMade = false;

        foreach ($node->getArgs() as $index => $arg) {
            if (! $arg instanceof Arg && ! $arg->value instanceof Closure) {
                continue;
            }

            $parameter = (is_string($index) ? $nameIndex[$index] : $parameters[$index]) ?? null;

            if ($this->updateClosureWithTypes($className, $parameter, $arg->value, $keyType, $valueType)) {
                $changesMade = true;
            }
        }

        if ($changesMade) {
            return $node;
        }

        return null;
    }

    private function updateClosureWithTypes(string $className, ParameterReflection $parameter, Closure $closure, Type $keyType, Type $valueType): bool
    {
        // get the ClosureType from the ParameterReflection
        $callableType = $this->typeUnwrapper->unwrapFirstCallableTypeFromUnionType($parameter->getType());

        if (! $callableType instanceof CallableType) {
            return false;
        }

        foreach ($callableType->getParameters() as $index => $parameterReflection) {
            $closureParameter = $closure->getParams()[$index] ?? null;

            if (! $closureParameter instanceof Param) {
                continue;
            }

            if (
                $this->typeUnwrapper->isIterableTypeValue($className, $parameterReflection->getType())
            ) {
                if ($this->refactorParameter($closureParameter, $valueType)) {
                    $changesMade = true;
                }
            } elseif (
                $this->typeUnwrapper->isIterableTypeKey($className, $parameterReflection->getType())
            ) {
                if ($this->refactorParameter($closureParameter, $keyType)) {
                    $changesMade = true;
                }
            }
        }

        return $changesMade;
    }

    private function refactorParameter(Param $param, Type $type): bool
    {
        // already set → no change
        if ($param->type instanceof Node) {
            $currentParamType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($param->type);
            if ($this->typeComparator->areTypesEqual($currentParamType, $type)) {
                return false;
            }
        }

        $paramTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($type, TypeKind::PARAM);
        $param->type = $paramTypeNode;

        return true;
    }

    /**
     * @param class-string $className
     * @param ParameterReflection[] $parameters
     */
    private function methodSignatureUsesCallableWithIteratorTypes(string $className, array $parameters): bool
    {
        foreach ($parameters as $parameter) {
            $callableType = $this->typeUnwrapper->unwrapFirstCallableTypeFromUnionType($parameter->getType());

            if (! $callableType instanceof CallableType) {
                continue;
            }

            foreach ($callableType->getParameters() as $parameterReflection) {
                if (
                    $this->typeUnwrapper->isIterableTypeValue($className, $parameterReflection->getType()) ||
                    $this->typeUnwrapper->isIterableTypeKey($className, $parameterReflection->getType())
                ) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param array<Arg|VariadicPlaceholder> $args
     */
    private function callUsesClosures(array $args): bool
    {
        foreach ($args as $arg) {
            if ($arg instanceof Arg && $arg->value instanceof Closure) {
                return true;
            }
        }

        return false;
    }
}
