<?php

declare(strict_types=1);

namespace Rector\Php71\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Parser;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParametersAcceptor;
use PHPStan\Reflection\Php\PhpFunctionReflection;
use PHPStan\Reflection\Php\PhpMethodReflection;
use PHPStan\Reflection\Type\UnionTypeMethodReflection;
use Rector\Core\PHPStan\Reflection\CallReflectionResolver;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Symplify\SmartFileSystem\SmartFileSystem;

/**
 * @changelog https://www.reddit.com/r/PHP/comments/a1ie7g/is_there_a_linter_for_argumentcounterror_for_php/
 * @changelog http://php.net/manual/en/class.argumentcounterror.php
 *
 * @see \Rector\Tests\Php71\Rector\FuncCall\RemoveExtraParametersRector\RemoveExtraParametersRectorTest
 */
final class RemoveExtraParametersRector extends AbstractRector
{
    public function __construct(
        private CallReflectionResolver $callReflectionResolver,
        private SmartFileSystem $smartFileSystem,
        private Parser $parser
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove extra parameters', [
            new CodeSample('strlen("asdf", 1);', 'strlen("asdf");'),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [FuncCall::class, MethodCall::class, StaticCall::class];
    }

    /**
     * @param FuncCall|MethodCall|StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        // unreliable count of arguments
        $functionLikeReflection = $this->callReflectionResolver->resolveCall($node);
        if ($functionLikeReflection instanceof UnionTypeMethodReflection) {
            return null;
        }

        if ($functionLikeReflection === null) {
            return null;
        }

        if ($functionLikeReflection instanceof PhpMethodReflection) {
            $classReflection = $functionLikeReflection->getDeclaringClass();
            if ($classReflection->isInterface()) {
                return null;
            }
        }

        $maximumAllowedParameterCount = $this->resolveMaximumAllowedParameterCount($functionLikeReflection);

        $numberOfArguments = count($node->args);
        if ($numberOfArguments <= $maximumAllowedParameterCount) {
            return null;
        }

        for ($i = $maximumAllowedParameterCount; $i <= $numberOfArguments; ++$i) {
            unset($node->args[$i]);
        }

        return $node;
    }

    /**
     * @param FuncCall|MethodCall|StaticCall $node
     */
    private function shouldSkip(Node $node): bool
    {
        if ($node->args === []) {
            return true;
        }

        if ($node instanceof StaticCall) {
            if (! $node->class instanceof Name) {
                return true;
            }

            if ($this->isName($node->class, 'parent')) {
                return true;
            }
        }

        $functionReflection = $this->callReflectionResolver->resolveCall($node);
        if ($functionReflection === null) {
            return true;
        }

        if ($functionReflection->getVariants() === []) {
            return true;
        }

        return $this->hasVariadicParameters($functionReflection, $functionReflection->getVariants());
    }

    /**
     * @param MethodReflection|FunctionReflection $reflection
     */
    private function resolveMaximumAllowedParameterCount(object $reflection): int
    {
        $parameterCounts = [0];
        foreach ($reflection->getVariants() as $parametersAcceptor) {
            $parameterCounts[] = count($parametersAcceptor->getParameters());
        }

        return (int) max($parameterCounts);
    }

    /**
     * @param MethodReflection|FunctionReflection $functionReflection
     * @param ParametersAcceptor[] $parameterAcceptors
     */
    private function hasVariadicParameters($functionReflection, array $parameterAcceptors): bool
    {
        foreach ($parameterAcceptors as $parameterAcceptor) {
            // can be any number of arguments → nothing to limit here
            if ($parameterAcceptor->isVariadic()) {
                return true;
            }
        }

        if ($functionReflection instanceof PhpFunctionReflection) {
            $pathsFunctionName = explode('\\', $functionReflection->getName());
            $functionName = array_pop($pathsFunctionName);

            $fileName = (string) $functionReflection->getFileName();
            /** @var Node[] $contentNodes */
            $contentNodes = $this->parser->parse($this->smartFileSystem->readFile($fileName));

            /** @var Function_ $function */
            $function = $this->betterNodeFinder->findFirst($contentNodes, function (Node $node) use ($functionName) {
                if (! $node instanceof Function_) {
                    return false;
                }

                return $this->isName($node, $functionName);
            });

            return (bool) $this->betterNodeFinder->findFirst($function->stmts, function (Node $node) {
                if (! $node instanceof FuncCall) {
                    return false;
                }

                return $this->isNames($node, ['func_get_args', 'func_num_args', 'func_get_arg']);
            });
        }

        return false;
    }
}
