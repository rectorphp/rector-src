<?php

declare(strict_types=1);

namespace Rector\Php81\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Identifier;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\Native\NativeFunctionReflection;
use Rector\NodeAnalyzer\ArgsAnalyzer;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PHPStan\ParametersAcceptorSelectorVariantsWrapper;
use Rector\Php81\Enum\NameNullToStrictNullFunctionMap;
use Rector\Php81\NodeManipulator\NullToStrictStringIntConverter;
use Rector\Rector\AbstractRector;
use Rector\Reflection\ReflectionResolver;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Php81\Rector\FuncCall\NullToStrictIntPregSlitFuncCallLimitArgRector\NullToStrictIntPregSlitFuncCallLimitArgRectorTest
 */
final class NullToStrictIntPregSlitFuncCallLimitArgRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly ReflectionResolver $reflectionResolver,
        private readonly ArgsAnalyzer $argsAnalyzer,
        private readonly NullToStrictStringIntConverter $nullToStrictStringConverter
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change null to strict int defined preg_split limit arg function call argument',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        preg_split('/\s/', $output, NULL, PREG_SPLIT_NO_EMPTY)
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        preg_split('/\s/', $output, 0, PREG_SPLIT_NO_EMPTY)
    }
}
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
        return [FuncCall::class];
    }

    /**
     * @param FuncCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        $scope = $node->getAttribute(AttributeKey::SCOPE);
        if (! $scope instanceof Scope) {
            return null;
        }

        $args = $node->getArgs();
        $position = $this->argsAnalyzer->hasNamedArg($args)
            ? $this->resolveNamedPosition($args)
            : 2;

        if ($position === null) {
            return null;
        }

        $classReflection = $scope->getClassReflection();
        $isTrait = $classReflection instanceof ClassReflection && $classReflection->isTrait();

        $functionReflection = $this->reflectionResolver->resolveFunctionLikeReflectionFromCall($node);
        if (! $functionReflection instanceof FunctionReflection) {
            return null;
        }

        $parametersAcceptor = ParametersAcceptorSelectorVariantsWrapper::select($functionReflection, $node, $scope);
        $result = $this->nullToStrictStringConverter->convertIfNull(
            $node,
            $args,
            (int) $position,
            $isTrait,
            $scope,
            $parametersAcceptor,
            'int'
        );

        if ($result instanceof Node) {
            return $result;
        }

        return null;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::DEPRECATE_NULL_ARG_IN_STRING_FUNCTION;
    }

    /**
     * @param Arg[] $args
     * @return ?int
     */
    private function resolveNamedPosition(array $args): ?int
    {
        foreach ($args as $position => $arg) {
            if (! $arg->name instanceof Identifier) {
                continue;
            }

            if (! $this->isName($arg->name, 'limit')) {
                continue;
            }

            return $position;
        }

        return null;
    }

    /**
     * @return int[]|string[]
     */
    private function resolveOriginalPositions(FuncCall $funcCall, Scope $scope): array
    {
        $functionReflection = $this->reflectionResolver->resolveFunctionLikeReflectionFromCall($funcCall);
        if (! $functionReflection instanceof NativeFunctionReflection) {
            return [];
        }

        $parametersAcceptor = ParametersAcceptorSelectorVariantsWrapper::select(
            $functionReflection,
            $funcCall,
            $scope
        );
        $functionName = $functionReflection->getName();
        $argNames = NameNullToStrictNullFunctionMap::FUNCTION_TO_PARAM_NAMES[$functionName];
        $positions = [];

        foreach ($parametersAcceptor->getParameters() as $position => $parameterReflection) {
            if (in_array($parameterReflection->getName(), $argNames, true)) {
                $positions[] = $position;
            }
        }

        return $positions;
    }

    private function shouldSkip(FuncCall $funcCall): bool
    {
        if (! $this->isName($funcCall, 'preg_split')) {
            return true;
        }

        return $funcCall->isFirstClassCallable();
    }
}
