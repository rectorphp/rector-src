<?php

declare(strict_types=1);

namespace Rector\Php71\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\Php\PhpMethodReflection;
use PHPStan\Reflection\Type\UnionTypeMethodReflection;
use Rector\Core\Enum\ObjectReference;
use Rector\Core\NodeAnalyzer\VariadicAnalyzer;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://www.reddit.com/r/PHP/comments/a1ie7g/is_there_a_linter_for_argumentcounterror_for_php/
 * @changelog http://php.net/manual/en/class.argumentcounterror.php
 *
 * @see \Rector\Tests\Php71\Rector\FuncCall\RemoveExtraParametersRector\RemoveExtraParametersRectorTest
 */
final class RemoveExtraParametersRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly VariadicAnalyzer $variadicAnalyzer,
        private readonly ReflectionResolver $reflectionResolver
    ) {
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::NO_EXTRA_PARAMETERS;
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
        $functionLikeReflection = $this->reflectionResolver->resolveFunctionLikeReflectionFromCall($node);
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
        if ($node->isFirstClassCallable()) {
            return null;
        }

        $numberOfArguments = count($node->getRawArgs());
        if ($numberOfArguments <= $maximumAllowedParameterCount) {
            return null;
        }

        for ($i = $maximumAllowedParameterCount; $i <= $numberOfArguments; ++$i) {
            unset($node->args[$i]);
        }

        return $node;
    }

    private function shouldSkip(FuncCall | MethodCall | StaticCall $call): bool
    {
        if ($call->args === []) {
            return true;
        }

        if ($call instanceof StaticCall) {
            if (! $call->class instanceof Name) {
                return true;
            }

            if ($this->isName($call->class, ObjectReference::PARENT)) {
                return true;
            }
        }

        return $this->variadicAnalyzer->hasVariadicParameters($call);
    }

    private function resolveMaximumAllowedParameterCount(
        MethodReflection | FunctionReflection $functionLikeReflection
    ): int {
        $parameterCounts = [0];
        foreach ($functionLikeReflection->getVariants() as $parametersAcceptor) {
            $parameterCounts[] = count($parametersAcceptor->getParameters());
        }

        return max($parameterCounts);
    }
}
