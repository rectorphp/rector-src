<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\FunctionLike;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Param;
use PhpParser\Node\VariadicPlaceholder;
use PHPStan\Reflection\Native\NativeFunctionReflection;
use PHPStan\Reflection\ParameterReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\ShouldNotHappenException;
use PHPStan\Type\ArrayType;
use PHPStan\Type\CallableType;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\PHPStanStaticTypeMapper\Utils\TypeUnwrapper;
use Rector\Rector\AbstractRector;
use Rector\Reflection\ReflectionResolver;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayMapRector\AddClosureParamTypeForArrayMapRectorTest
 */
final class AddClosureParamTypeForArrayMapRector extends AbstractRector
{
    public function __construct(
        private readonly TypeComparator $typeComparator,
        private readonly StaticTypeMapper $staticTypeMapper,
        private readonly ReflectionResolver $reflectionResolver,
        private readonly TypeUnwrapper $typeUnwrapper,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Applies type hints to array_map closures',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
array_map(function ($value, $key): string {
    return $value . $key;
}, $strings);
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
array_map(function (string $value, int $key): bool {
    return $value . $key;
}, $strings);
CODE_SAMPLE
                    ,
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    /**
     * @param FuncCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->isFirstClassCallable()) {
            return null;
        }

        if (! $this->isName($node,'array_map')) {
            return null;
        }

        $funcReflection = $this->reflectionResolver->resolveFunctionLikeReflectionFromCall($node);

        if (! $funcReflection instanceof NativeFunctionReflection) {
            return null;
        }

        if (! $node->args[0]->value instanceof Closure) {
            return null;
        }

        /** @var ArrayType[] $types */
        $types = array_filter(array_map(function (Arg $arg): ?ArrayType {
            $type = $this->getType($arg->value);

            if ($type instanceof ArrayType) {
                return $type;
            }

            return null;
        }, array_slice($node->args, 1)));

        $values = [];
        $keys = [];

        foreach ($types as $type) {
           $values[] = $type->getIterableValueType();
           $keys[] = $type->getIterableKeyType();
        }

        foreach ($values as $value) {
            if ($value instanceof MixedType) {
                $values = [];
                break;
            }
        }

        foreach ($keys as $key) {
            if ($key instanceof MixedType) {
                $keys = [];
                break;
            }
        }

        $valueType = $this->combineTypes($values);
        $keyType = $this->combineTypes($keys);

        if (! $keyType instanceof Type && ! $valueType instanceof Type) {
            return null;
        }

        $this->updateClosureWithTypes($node->args[0]->value, $keyType, $valueType);

        return $node;
    }

    private function updateClosureWithTypes(
        Closure $closure,
        ?Type $keyType,
        ?Type $valueType
    ): void {
        if ($closure->params[0] ?? null instanceof Param) {
            $this->refactorParameter($closure->params[0], $valueType);
        }
        if ($closure->params[1] ?? null instanceof Param) {
            $this->refactorParameter($closure->params[1], $keyType);
        }
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

        if (! $paramTypeNode instanceof Node) {
            return false;
        }

        $param->type = $paramTypeNode;

        return true;
    }

    /**
     * @param Type[] $types
     * @throws ShouldNotHappenException
     */
    private function combineTypes(array $types): Type
    {
        $types = array_reduce($types, function(array $types, Type $type): array {
            foreach ($types as $previousType) {
                if ($this->typeComparator->areTypesEqual($type, $previousType)) {
                    return $types;
                }
            }

            $types[] = $type;
            return $types;
        }, []);

        if (count($types) === 1) {
            return $types[0];
        }

        return new UnionType($types);
    }
}
