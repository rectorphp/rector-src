<?php

declare(strict_types=1);

namespace Rector\Renaming\Rector\FunctionLike;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Identifier;
use PhpParser\Node\Param;
use PhpParser\NodeTraverser;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Rector\Renaming\ValueObject\RenameFunctionLikeParamWithinCallLikeArg;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\Renaming\Rector\FunctionLike\RenameFunctionLikeParamWithinCallLikeArgRector\RenameFunctionLikeParamWithinCallLikeArgRectorTest
 */
final class RenameFunctionLikeParamWithinCallLikeArgRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var RenameFunctionLikeParamWithinCallLikeArg[]
     */
    private array $renameFunctionLikeParamWithinCallLikeParams = [];

    private bool $hasChanged = false;

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add param types where needed', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
(new SomeClass)->process(function ($param) {});
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
(new SomeClass)->process(function ($parameter) {});
CODE_SAMPLE
                ,
                [new RenameFunctionLikeParamWithinCallLikeArg('SomeClass', 'process', 0, 0, 'parameter')]
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class, StaticCall::class];
    }

    /**
     * @param CallLike $node
     */
    public function refactor(Node $node): ?Node
    {
        $this->hasChanged = false;
        foreach ($this->renameFunctionLikeParamWithinCallLikeParams as $renameFunctionLikeParamWithinCallLikeParam) {
            $type = match (true) {
                $node instanceof MethodCall => $node->var,
                $node instanceof StaticCall => $node->class,
                default => null,
            };

            if ($type === null) {
                continue;
            }

            if (! $this->isObjectType($type, $renameFunctionLikeParamWithinCallLikeParam->getObjectType())) {
                continue;
            }

            if (($node->name ?? null) === null) {
                continue;
            }

            if (! $node->name instanceof Identifier) {
                continue;
            }

            if (! $this->isName($node->name, $renameFunctionLikeParamWithinCallLikeParam->getMethodName())) {
                continue;
            }

            $this->processFunctionLike($node, $renameFunctionLikeParamWithinCallLikeParam);
        }

        if (! $this->hasChanged) {
            return null;
        }

        return $node;
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, RenameFunctionLikeParamWithinCallLikeArg::class);

        $this->renameFunctionLikeParamWithinCallLikeParams = $configuration;
    }

    private function processFunctionLike(
        CallLike $callLike,
        RenameFunctionLikeParamWithinCallLikeArg $renameFunctionLikeParamWithinCallLikeArg
    ): void {
        if (is_int($renameFunctionLikeParamWithinCallLikeArg->getCallLikePosition())) {
            $arg = $this->processPositionalArg($callLike, $renameFunctionLikeParamWithinCallLikeArg);
        } else {
            $arg = $this->processNamedArg($callLike, $renameFunctionLikeParamWithinCallLikeArg);
        }

        if (! $arg instanceof Arg) {
            return;
        }

        $functionLike = $arg->value;
        if (! $functionLike instanceof FunctionLike) {
            return;
        }

        if (! isset($functionLike->params[$renameFunctionLikeParamWithinCallLikeArg->getFunctionLikePosition()])) {
            return;
        }

        $this->refactorParameter(
            $functionLike->params[$renameFunctionLikeParamWithinCallLikeArg->getFunctionLikePosition()],
            $functionLike,
            $renameFunctionLikeParamWithinCallLikeArg
        );
    }

    /**
     * Rename the Parameter variable name
     */
    private function refactorParameter(
        Param $param,
        FunctionLike $functionLike,
        RenameFunctionLikeParamWithinCallLikeArg $renameFunctionLikeParamWithinCallLikeArg
    ): void {
        if (! $param->var instanceof Variable) {
            return;
        }

        if (! is_string($param->var->name)) {
            return;
        }

        $oldName = $param->var->name;

        // skip if the name is in use within the context of the function like
        if ($this->isVariableNameUsedInFunctionLike(
            $functionLike,
            $oldName,
            $renameFunctionLikeParamWithinCallLikeArg->getNewParamName()
        )) {
            return;
        }

        $param->var->name = $renameFunctionLikeParamWithinCallLikeArg->getNewParamName();

        // refactor the FunctionLike usage of the variable
        $this->traverseNodesWithCallable($functionLike, function (Node $node) use (
            $oldName,
            $renameFunctionLikeParamWithinCallLikeArg
        ): ?Node {
            if (! $node instanceof Variable) {
                return null;
            }

            if (! $this->isName($node, $oldName)) {
                return null;
            }

            $node->name = $renameFunctionLikeParamWithinCallLikeArg->getNewParamName();

            return $node;
        });

        $this->hasChanged = true;
    }

    private function isVariableNameUsedInFunctionLike(
        FunctionLike $functionLike,
        string $oldName,
        string $newName
    ): bool {
        $isUsed = false;
        $this->traverseNodesWithCallable($functionLike, function (Node $node) use (
            $functionLike,
            $oldName,
            $newName,
            &$isUsed
        ): ?int {
            if ($node instanceof FunctionLike && $node !== $functionLike) {
                if ($node instanceof Closure && $node->uses !== [] && $this->isClosureUsingParam($node, $oldName)) {
                    $isUsed = true;
                }

                return NodeTraverser::DONT_TRAVERSE_CHILDREN;
            }

            if (! $node instanceof Variable) {
                return null;
            }

            if ($this->isName($node, $newName)) {
                $isUsed = true;
            }

            return null;
        });

        return $isUsed;
    }

    private function isClosureUsingParam(Closure $node, string $oldName): bool
    {
        foreach ($node->uses as $use) {
            if ($this->isName($use->var, $oldName)) {
                return true;
            }
        }

        return false;
    }

    private function processPositionalArg(
        CallLike $callLike,
        RenameFunctionLikeParamWithinCallLikeArg $renameFunctionLikeParamWithinCallLikeArg
    ): ?Arg {
        if ($callLike->getArgs() === []) {
            return null;
        }

        $arg = $callLike->args[$renameFunctionLikeParamWithinCallLikeArg->getCallLikePosition()] ?? null;

        if (! $arg instanceof Arg) {
            return null;
        }

        // int positions shouldn't have names
        if ($arg->name !== null) {
            return null;
        }

        return $arg;
    }

    private function processNamedArg(
        CallLike $callLike,
        RenameFunctionLikeParamWithinCallLikeArg $renameFunctionLikeParamWithinCallLikeArg
    ): ?Arg {
        $args = array_filter($callLike->getArgs(), static function (Arg $arg) use (
            $renameFunctionLikeParamWithinCallLikeArg
        ): bool {
            if ($arg->name === null) {
                return false;
            }

            return $arg->name->name === $renameFunctionLikeParamWithinCallLikeArg->getCallLikePosition();
        });

        if ($args === []) {
            return null;
        }

        return array_values($args)[0];
    }
}
