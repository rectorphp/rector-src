<?php

declare(strict_types=1);

namespace Rector\DowngradePhp80\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParameterReflection;
use PHPStan\Type\Type;
use PHPStan\Type\VerbosityLevel;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\DowngradePhp80\NodeAnalyzer\UnnamedArgumentResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DowngradePhp80\Rector\MethodCall\DowngradeNamedArgumentRector\DowngradeNamedArgumentRectorTest
 */
final class DowngradeNamedArgumentRector extends AbstractRector
{
    public function __construct(
        private ReflectionResolver $reflectionResolver,
        private UnnamedArgumentResolver $unnamedArgumentResolver
    ) {
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class, StaticCall::class, New_::class];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove named argument',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $this->execute(b: 100);
    }

    private function execute($a = null, $b = null)
    {
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $this->execute(null,  100);
    }

    private function execute($a = null, $b = null)
    {
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @param MethodCall|StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        $args = $node->args;
        if ($this->shouldSkip($args)) {
            return null;
        }

        $this->removeNamedArguments($node, $args);
        return $node;
    }

    /**
     * @param Arg[] $args
     */
    private function removeNamedArguments(MethodCall | StaticCall | New_ $node, array $args): ?Node
    {
        if ($node instanceof New_) {
            $methodReflection = $this->reflectionResolver->resolveMethodReflectionFromNew($node);
            if (! $methodReflection instanceof MethodReflection) {
                return null;
            }

            return $this->processRemoveNamedArgument($methodReflection, $node, $args);
        }

        $functionLikeReflection = $this->reflectionResolver->resolveFunctionLikeReflectionFromCall($node);
        if ($functionLikeReflection === null) {
            return null;
        }

        return $this->processRemoveNamedArgument($functionLikeReflection, $node, $args);
    }

    /**
     * @param Arg[] $args
     */
    private function processRemoveNamedArgument(
        MethodReflection | FunctionReflection $functionLikeReflection,
        MethodCall | StaticCall | New_ $node,
        array $args
    ): MethodCall | StaticCall | New_ | null {
        $unnmaedArgs = $this->unnamedArgumentResolver->resolveFromReflection($functionLikeReflection, $args);
        $parametersAcceptor = $functionLikeReflection->getVariants()[0] ?? null;

        $this->replacePreviousArgs($node, $parametersAcceptor->getParameters(), $unnmaedArgs);
        return $node;
    }

    /**
     * @param ParameterReflection[] $parameterReflections
     * @param Arg[] $newArgs
     */
    private function replacePreviousArgs(
        MethodCall | StaticCall | New_ $node,
        array $parameterReflections,
        array $newArgs
    ): void {
        $parameterPosition = count($newArgs);

        for ($i = $parameterPosition - 1; $i >= 0; --$i) {
            $parameterReflection = $parameterReflections[$i] ?? null;
            if (! $parameterReflection instanceof ParameterReflection) {
                continue;
            }

            if ($parameterReflection->getDefaultValue() === null) {
                continue;
            }

            $defaultValue = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode(
                $parameterReflection->getDefaultValue()
            );
            if (! $defaultValue instanceof Expr) {
                continue;
            }

            if (isset($newArgs[$i])) {
                continue;
            }

            $newArgs[$i] = new Arg($defaultValue);
        }

        $countNewArgs = count($newArgs);
        for ($i = 0; $i < $countNewArgs; ++$i) {
            if (! isset($newArgs[$i])) {
                continue;
            }

            $node->args[$i] = $newArgs[$i];
        }
    }

    /**
     * @param Arg[] $args
     */
    private function shouldSkip(array $args): bool
    {
        foreach ($args as $arg) {
            if ($arg->name instanceof Identifier) {
                return false;
            }
        }

        return true;
    }

//    private function mapPHPStanTypeToExpr(?Type $type): ?Expr
//    {
//        if ($type === null) {
//            return null;
//        }
//
//        return new ConstFetch(new Name($type->describe(VerbosityLevel::value())));
//    }


//    private function shouldSkipParam(Arg $arg, ParameterReflection $parameterReflection): bool
//    {
//        if (! $this->isName($arg, $parameterReflection->getName())) {
//            return true;
//        }
//
//        return $this->areArgValueAndParameterDefaultValueEqual($parameterReflection, $arg);
//    }
}
