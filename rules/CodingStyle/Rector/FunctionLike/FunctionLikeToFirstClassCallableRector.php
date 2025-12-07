<?php

declare(strict_types=1);

namespace Rector\CodingStyle\Rector\FunctionLike;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\Expr\Closure;
use Rector\CodingStyle\Rector\ArrowFunction\ArrowFunctionDelegatingCallToFirstClassCallableRector;
use Rector\CodingStyle\Rector\Closure\ClosureDelegatingCallToFirstClassCallableRector;
use Rector\Configuration\Deprecation\Contract\DeprecatedInterface;
use Rector\Exception\ShouldNotHappenException;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @deprecated This rule was split into
 * @see ClosureDelegatingCallToFirstClassCallableRector and
 * @see ArrowFunctionDelegatingCallToFirstClassCallableRector
 */
final class FunctionLikeToFirstClassCallableRector extends AbstractRector implements MinPhpVersionInterface, DeprecatedInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Converts arrow function and closures to first class callable',
            [new CodeSample(
                <<<'CODE_SAMPLE'
function ($parameter) {
    return Call::to($parameter);
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
Call::to(...);
CODE_SAMPLE
                ,
            )]
        );
    }

    public function getNodeTypes(): array
    {
        return [ArrowFunction::class, Closure::class];
    }

    /**
     * @param ArrowFunction|Closure $node
     */
    public function refactor(Node $node): null|CallLike
    {
<<<<<<< HEAD
        throw new ShouldNotHappenException(sprintf(
            '"%s" rule is deprecated. It was split into "%s" and "%s" rules.',
            self::class,
            ClosureDelegatingCallToFirstClassCallableRector::class,
            ArrowFunctionDelegatingCallToFirstClassCallableRector::class
        ));
=======
        if ($node instanceof Assign) {
            // @todo handle by existing attribute already
            if ($node->expr instanceof Closure || $node->expr instanceof ArrowFunction) {
                $node->expr->setAttribute(self::IS_IN_ASSIGN, true);
            }

            return null;
        }

        if ($node instanceof CallLike) {
            if ($node->isFirstClassCallable()) {
                return null;
            }

            $methodReflection = $this->reflectionResolver->resolveFunctionLikeReflectionFromCall($node);
            foreach ($node->getArgs() as $arg) {
                if (! $arg->value instanceof Closure && ! $arg->value instanceof ArrowFunction) {
                    continue;
                }

                if ($methodReflection instanceof NativeFunctionReflection) {
                    $parametersAcceptors = ParametersAcceptorSelector::combineAcceptors(
                        $methodReflection->getVariants()
                    );
                    foreach ($parametersAcceptors->getParameters() as $extendedParameterReflection) {
                        if ($extendedParameterReflection->getType() instanceof CallableType && $extendedParameterReflection->getType()->isVariadic()) {
                            $arg->value->setAttribute(self::HAS_CALLBACK_SIGNATURE_MULTI_PARAMS, true);
                        }
                    }

                    return null;
                }

                $arg->value->setAttribute(self::HAS_CALLBACK_SIGNATURE_MULTI_PARAMS, true);
            }

            return null;
        }

        $callLike = $this->extractCallLike($node);
        if ($callLike === null) {
            return null;
        }

        if ($this->shouldSkip($node, $callLike, ScopeFetcher::fetch($node))) {
            return null;
        }

        $callLike->args = [new VariadicPlaceholder()];

        return $callLike;
>>>>>>> 24ed2fa7ac (add fixture to keep protected method)
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::FIRST_CLASS_CALLABLE_SYNTAX;
    }
}
