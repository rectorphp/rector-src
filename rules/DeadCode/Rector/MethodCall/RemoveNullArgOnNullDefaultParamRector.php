<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
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
        return [MethodCall::class, StaticCall::class, New_::class];
    }

    /**
     * @param MethodCall|StaticCall|New_ $node
     */
    public function refactor(Node $node): StaticCall|MethodCall|New_|null
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

            $nullPositions = $this->callLikeParamDefaultResolver->resolveNullPositions($node);
            if (! in_array($position, $nullPositions)) {
                continue;
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
