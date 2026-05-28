<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use Rector\DeadCode\NodeAnalyzer\CallLikeParamDefaultResolver;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\MethodCall\RemoveNullNamedArgOnNullDefaultParamRector\RemoveNullNamedArgOnNullDefaultParamRectorTest
 */
final class RemoveNullNamedArgOnNullDefaultParamRector extends AbstractRector
{
    public function __construct(
        private readonly ValueResolver $valueResolver,
        private readonly CallLikeParamDefaultResolver $callLikeParamDefaultResolver,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove named null argument, where null is already a default param value',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function call(ExternalClass $externalClass)
    {
        $externalClass->execute(value: 1, someClass: null);
    }
}

class ExternalClass
{
    public function execute(int $value, ?SomeClass $someClass = null)
    {
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function call(ExternalClass $externalClass)
    {
        $externalClass->execute(value: 1);
    }
}

class ExternalClass
{
    public function execute(int $value, ?SomeClass $someClass = null)
    {
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [MethodCall::class, StaticCall::class, New_::class, FuncCall::class];
    }

    /**
     * @param MethodCall|StaticCall|New_|FuncCall $node
     */
    public function refactor(Node $node): StaticCall|MethodCall|New_|FuncCall|null
    {
        if ($node->isFirstClassCallable()) {
            return null;
        }

        $args = $node->getArgs();
        if ($args === []) {
            return null;
        }

        $hasNamedArg = false;
        foreach ($args as $arg) {
            if ($arg->name instanceof Identifier) {
                $hasNamedArg = true;
                break;
            }
        }

        if (! $hasNamedArg) {
            return null;
        }

        $nullPositions = $this->callLikeParamDefaultResolver->resolveNullPositions($node);
        if ($nullPositions === []) {
            return null;
        }

        // map every arg to its target parameter position, so named args in any order are handled
        $argPositionByParameterPosition = [];
        foreach ($args as $argPosition => $arg) {
            if ($arg->unpack) {
                return null;
            }

            if ($arg->name instanceof Identifier) {
                $parameterPosition = $this->callLikeParamDefaultResolver->resolvePositionParameterByName(
                    $node,
                    $arg->name->toString()
                );

                if ($parameterPosition === null) {
                    return null;
                }
            } else {
                $parameterPosition = $argPosition;
            }

            $argPositionByParameterPosition[$parameterPosition] = $argPosition;
        }

        // only handle calls that fill a contiguous prefix of parameters, so a lone misplaced named arg is left untouched
        ksort($argPositionByParameterPosition);
        if (array_keys($argPositionByParameterPosition) !== range(0, count($args) - 1)) {
            return null;
        }

        $hasChanged = false;
        foreach ($argPositionByParameterPosition as $parameterPosition => $argPosition) {
            $arg = $args[$argPosition];

            // only named args are removed here; remaining args still bind by name
            if (! $arg->name instanceof Identifier) {
                continue;
            }

            if (! $this->valueResolver->isNull($arg->value)) {
                continue;
            }

            if (! in_array($parameterPosition, $nullPositions, true)) {
                continue;
            }

            unset($node->args[$argPosition]);
            $hasChanged = true;
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }
}
