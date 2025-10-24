<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Type\NullType;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Rector\Reflection\ReflectionResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\MethodCall\RemoveNullArgOnNullDefaultParamRector\RemoveNullArgOnNullDefaultParamRectorTest
 */
final class RemoveNullArgOnNullDefaultParamRector extends AbstractRector
{
    public function __construct(
        private readonly ValueResolver $valueResolver,
        private readonly ReflectionResolver $reflectionResolver,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove default null argument, where null is already a default param value', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function call(ExternalClass $externalClass)
    {
        $externalClass->execute(null);
    }
}

class ExternalClass
{
    public function execute(?SomeClass $someClass = null)
    {
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'

class SomeClass
{
    public function call(ExternalClass $externalClass)
    {str
        $externalClass->execute();
    }
}

class ExternalClass
{
    public function execute(?SomeClass $someClass = null)
    {
    }
}
CODE_SAMPLE
            ),

        ]);
    }

    public function getNodeTypes(): array
    {
        return [MethodCall::class, StaticCall::class];
    }

    /**
     * @param MethodCall|StaticCall $node
     */
    public function refactor(Node $node): StaticCall|MethodCall|null
    {
        if ($node->isFirstClassCallable()) {
            return null;
        }

        if ($node->getArgs() === []) {
            return null;
        }

        $hasChanged = false;

        foreach ($node->getArgs() as $position => $arg) {
            if ($arg->unpack) {
                continue;
            }

            // skip named args
            if ($arg->name instanceof Node) {
                continue;
            }

            if (! $this->valueResolver->isNull($arg->value)) {
                continue;
            }

            // @todo extract to service
            $nullPositions = $this->resolveDefaultParamNullPositions($node);

            if (! in_array($position, $nullPositions)) {
                continue;
            }

            unset($node->args[0]);

            $hasChanged = true;
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    /**
     * @return int[]
     */
    private function resolveDefaultParamNullPositions(MethodCall|StaticCall $callLike): array
    {

        if ($callLike instanceof StaticCall) {
            $methodReflection = $this->reflectionResolver->resolveMethodReflectionFromStaticCall($callLike);
        } else {
            $methodReflection = $this->reflectionResolver->resolveMethodReflectionFromMethodCall($callLike);
        }

        if (! $methodReflection instanceof MethodReflection) {
            return [];
        }

        $nullPositions = [];

        $extendedParametersAcceptor = ParametersAcceptorSelector::combineAcceptors($methodReflection->getVariants());
        foreach ($extendedParametersAcceptor->getParameters() as $position => $extendedParameterReflection) {
            if (! $extendedParameterReflection->getDefaultValue() instanceof NullType) {
                continue;
            }

            $nullPositions[] = $position;
        }

        return $nullPositions;
    }
}
