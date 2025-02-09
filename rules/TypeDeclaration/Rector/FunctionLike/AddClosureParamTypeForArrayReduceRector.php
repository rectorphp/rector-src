<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\FunctionLike;

use PhpParser\Node;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Param;
use PHPStan\Reflection\Native\NativeFunctionReflection;
use PHPStan\Type\ClosureType;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\Rector\AbstractRector;
use Rector\Reflection\ReflectionResolver;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayReduceRector\AddClosureParamTypeForArrayReduceRectorTest
 */
final class AddClosureParamTypeForArrayReduceRector extends AbstractRector
{
    public function __construct(
        private readonly TypeComparator $typeComparator,
        private readonly StaticTypeMapper $staticTypeMapper,
        private readonly ReflectionResolver $reflectionResolver,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Applies type hints to array_map closures',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
array_reduce($strings, function ($carry, $value, $key): string {
    return $carry . $value;
}, $initialString);
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
array_reduce($strings, function (string $carry, string $value): string {
    return $carry . $value;
}, $initialString);
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

        if (! $this->isName($node, 'array_reduce')) {
            return null;
        }

        $funcReflection = $this->reflectionResolver->resolveFunctionLikeReflectionFromCall($node);

        if (! $funcReflection instanceof NativeFunctionReflection) {
            return null;
        }

        $args = $node->getArgs();

        if (! isset($args[1]) || ! $args[1]->value instanceof Closure) {
            return null;
        }

        $closureType = $this->getType($args[1]->value);
        if (! $closureType instanceof ClosureType) {
            return null;
        }

        $returnType = $closureType->getReturnType();

        $type = $this->getType($args[0]->value);

        $valueType = $type->getIterableValueType();

        if ($this->updateClosureWithTypes($args[1]->value, $valueType, $returnType)) {
            return $node;
        }

        return null;
    }

    private function updateClosureWithTypes(Closure $closure, ?Type $valueType, ?Type $carryType): bool
    {
        $changes = false;
        $carryParam = $closure->params[0] ?? null;
        $valueParam = $closure->params[1] ?? null;

        if ($valueParam instanceof Param && $valueType instanceof Type && $this->refactorParameter(
            $valueParam,
            $valueType
        )) {
            $changes = true;
        }

        if ($carryParam instanceof Param && $carryType instanceof Type && $this->refactorParameter(
            $carryParam,
            $carryType
        )) {
            return true;
        }

        return $changes;
    }

    private function refactorParameter(Param $param, Type $type): bool
    {
        if ($type instanceof MixedType) {
            return false;
        }

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
}
