<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Identifier;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use Rector\NodeAnalyzer\ArgsAnalyzer;
use Rector\Rector\AbstractRector;
use Rector\Reflection\ReflectionResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodeQuality\Rector\FuncCall\SortNamedParamRector\SortNamedParamRectorTest
 */
final class SortNamedParamRector extends AbstractRector
{
    public function __construct(
        private readonly ReflectionResolver $reflectionResolver,
        private readonly ArgsAnalyzer $argsAnalyzer
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change array_merge of non arrays to array directly',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
function run($foo = null, $bar = null, $baz = null) {}

run(bar: $bar, foo: $foo);

run($foo, baz: $baz, bar: $bar);
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
function run($foo = null, $bar = null, $baz = null) {}

run(foo: $foo, bar: $bar);

run($foo, bar: $bar, baz: $baz);
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class, StaticCall::class, New_::class, FuncCall::class];
    }

    /**
     * @param MethodCall|StaticCall|New_|FuncCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->isFirstClassCallable()) {
            return null;
        }

        $args = $node->getArgs();
        if (! $this->argsAnalyzer->hasNamedArg($args)) {
            return null;
        }

        if ($node instanceof New_) {
            $functionLikeReflection = $this->reflectionResolver->resolveMethodReflectionFromNew($node);
        } else {
            $functionLikeReflection = $this->reflectionResolver->resolveFunctionLikeReflectionFromCall($node);
        }

        if (! $functionLikeReflection instanceof MethodReflection && ! $functionLikeReflection instanceof FunctionReflection) {
            return null;
        }

        $args = $this->sortNamedArguments($functionLikeReflection, $args);
        if ($node->args === $args) {
            return null;
        }

        $node->args = $args;

        return $node;
    }

    /**
     * @param Arg[] $currentArgs
     * @return Arg[]
     */
    public function sortNamedArguments(
        FunctionReflection | MethodReflection $functionLikeReflection,
        array $currentArgs
    ): array {
        $extendedParametersAcceptor = ParametersAcceptorSelector::combineAcceptors(
            $functionLikeReflection->getVariants()
        );

        $parameters = $extendedParametersAcceptor->getParameters();

        $order = [];
        foreach ($parameters as $key => $parameter) {
            $order[$parameter->getName()] = $key;
        }

        $sortedArgs = [];
        $toSortArgs = [];
        foreach ($currentArgs as $currentArg) {
            if (!$currentArg->name instanceof Identifier) {
                $sortedArgs[] = $currentArg;
                continue;
            }

            $toSortArgs[] = $currentArg;
        }

        usort(
            $toSortArgs,
            static function (Arg $arg1, Arg $arg2) use ($order): int {
                /** @var Identifier $argName1 */
                $argName1 = $arg1->name;
                /** @var Identifier $argName2 */
                $argName2 = $arg2->name;

                return $order[$argName1->name] <=> $order[$argName2->name];
            }
        );

        return [...$sortedArgs, ...$toSortArgs];
    }
}
