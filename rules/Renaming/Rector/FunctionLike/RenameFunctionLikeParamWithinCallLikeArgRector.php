<?php

declare(strict_types=1);

namespace Rector\Renaming\Rector\FunctionLike;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Identifier;
use PhpParser\Node\Param;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Naming\Guard\BreakingVariableRenameGuard;
use Rector\Naming\ParamRenamer\ParamRenamer;
use Rector\Naming\ValueObject\ParamRename;
use Rector\Naming\ValueObjectFactory\ParamRenameFactory;
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
    private array $renameFunctionLikeParamWithinCallLikeArgs = [];

    public function __construct(
        private readonly BreakingVariableRenameGuard $breakingVariableRenameGuard,
        private readonly ParamRenamer $paramRenamer,
        private readonly ParamRenameFactory $paramRenameFactory,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Rename param within closures and arrow functions based on use with specified method calls',
            [
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
        $hasChanged = false;

        foreach ($this->renameFunctionLikeParamWithinCallLikeArgs as $renameFunctionLikeParamWithinCallLikeArg) {
            if (! $node instanceof MethodCall && ! $node instanceof StaticCall) {
                continue;
            }

            $type = match (true) {
                $node instanceof MethodCall => $node->var,
                $node instanceof StaticCall => $node->class,
            };

            if (! $this->isObjectType($type, $renameFunctionLikeParamWithinCallLikeArg->getObjectType())) {
                continue;
            }

            if (($node->name ?? null) === null) {
                continue;
            }

            if (! $node->name instanceof Identifier) {
                continue;
            }

            if (! $this->isName($node->name, $renameFunctionLikeParamWithinCallLikeArg->getMethodName())) {
                continue;
            }

            $arg = $this->findArgFromMethodCall($renameFunctionLikeParamWithinCallLikeArg, $node);
            $functionLike = $arg?->value;

            if (! $arg instanceof Arg) {
                continue;
            }

            if (! $functionLike instanceof FunctionLike) {
                continue;
            }

            $param = $this->findParameterFromArg($arg, $renameFunctionLikeParamWithinCallLikeArg);

            if (! $param instanceof Param) {
                continue;
            }

            if (! $param->var instanceof Variable) {
                continue;
            }

            if (
                ($functionLike instanceof Closure || $functionLike instanceof ArrowFunction) &&
                (
                    $this->breakingVariableRenameGuard->shouldSkipVariable(
                        (string) $this->nodeNameResolver->getName($param->var),
                        $renameFunctionLikeParamWithinCallLikeArg->getNewParamName(),
                        $functionLike,
                        $param->var
                    )
                )
            ) {
                continue;
            }

            $paramRename = $this->paramRenameFactory->createFromResolvedExpectedName(
                $functionLike,
                $param,
                $renameFunctionLikeParamWithinCallLikeArg->getNewParamName()
            );

            if (! $paramRename instanceof ParamRename) {
                continue;
            }

            $this->paramRenamer->rename($paramRename);

            $hasChanged = true;
        }

        if (! $hasChanged) {
            return null;
        }

        return $node;
    }

    /**
     * @param RenameFunctionLikeParamWithinCallLikeArg[] $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, RenameFunctionLikeParamWithinCallLikeArg::class);

        $this->renameFunctionLikeParamWithinCallLikeArgs = $configuration;
    }

    public function findParameterFromArg(
        Arg $arg,
        RenameFunctionLikeParamWithinCallLikeArg $renameFunctionLikeParamWithinCallLikeArg
    ): ?Param {
        $functionLike = $arg->value;
        if (! $functionLike instanceof FunctionLike) {
            return null;
        }

        return $functionLike->params[$renameFunctionLikeParamWithinCallLikeArg->getFunctionLikePosition()] ?? null;
    }

    private function findArgFromMethodCall(
        RenameFunctionLikeParamWithinCallLikeArg $renameFunctionLikeParamWithinCallLikeArg,
        CallLike $callLike
    ): ?Arg {
        if (is_int($renameFunctionLikeParamWithinCallLikeArg->getCallLikePosition())) {
            return $this->processPositionalArg($callLike, $renameFunctionLikeParamWithinCallLikeArg);
        }

        return $this->processNamedArg($callLike, $renameFunctionLikeParamWithinCallLikeArg);
    }

    private function processPositionalArg(
        CallLike $callLike,
        RenameFunctionLikeParamWithinCallLikeArg $renameFunctionLikeParamWithinCallLikeArg
    ): ?Arg {
        if ($callLike->isFirstClassCallable()) {
            return null;
        }

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
