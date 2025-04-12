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
use Rector\Enum\ObjectReference;
use Rector\NodeAnalyzer\VariadicAnalyzer;
use Rector\Rector\AbstractRector;
use Rector\Reflection\ReflectionResolver;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
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
            if ($functionLikeReflection->isAbstract()) {
                return null;
            }

            $classReflection = $functionLikeReflection->getDeclaringClass();
            if ($classReflection->isInterface()) {
                return null;
            }
        }

        $maximumAllowedParameterCount = $this->resolveMaximumAllowedParameterCount($functionLikeReflection);
        if ($node->isFirstClassCallable()) {
            return null;
        }

        if ($this->shouldSkipFunctionReflection($functionLikeReflection)) {
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

    private function shouldSkipFunctionReflection(MethodReflection|FunctionReflection $reflection): bool
    {
        if ($reflection instanceof FunctionReflection) {
            $fileName = (string) $reflection->getFileName();
            if (str_contains($fileName, 'phpstan.phar')) {
                return true;
            }
        }

        if ($reflection instanceof MethodReflection) {
            $classReflection = $reflection->getDeclaringClass();
            $fileName = (string) $classReflection->getFileName();
            if (str_contains($fileName, 'phpstan.phar')) {
                return \true;
            }
        }

        return false;
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
        foreach ($functionLikeReflection->getVariants() as $variant) {
            $parameterCounts[] = count($variant->getParameters());
        }

        return max($parameterCounts);
    }
}
