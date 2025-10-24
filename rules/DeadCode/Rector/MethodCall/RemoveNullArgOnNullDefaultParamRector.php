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
 * @see \Rector\Tests\DeadCode\Rector\MethodCall\RemoveNullArgOnNullDefaultParamRector\RemoveNullArgOnNullDefaultParamRectorTest
 */
final class RemoveNullArgOnNullDefaultParamRector extends AbstractRector
{
    public function __construct(
        private readonly ValueResolver $valueResolver,
        private readonly CallLikeParamDefaultResolver $callLikeParamDefaultResolver,
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

        if ($node->getArgs() === []) {
            return null;
        }

        $hasChanged = false;

        $args = $node->getArgs();
        $lastArgPosition = count($args) - 1;
        for ($position = $lastArgPosition; $position >= 0; --$position) {
            if (! isset($args[$position])) {
                continue;
            }

            $arg = $args[$position];
            if ($arg->unpack) {
                break;
            }

            // stop when found named arg and position not match
            if ($arg->name instanceof Identifier &&
                $position !== $this->callLikeParamDefaultResolver->resolvePositionParameterByName(
                    $node,
                    $arg->name->toString()
                )) {
                break;
            }

            if (! $this->valueResolver->isNull($arg->value)) {
                break;
            }

            $nullPositions = $this->callLikeParamDefaultResolver->resolveNullPositions($node);
            if (! in_array($position, $nullPositions)) {
                break;
            }

            unset($node->args[$position]);
            $hasChanged = true;
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }
}
