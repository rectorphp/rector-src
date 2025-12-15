<?php

declare(strict_types=1);

namespace Rector\PhpParser\NodeVisitor;

use PhpParser\Node\Arg;
use PhpParser\Node;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\Expr\Closure;
use PhpParser\NodeVisitorAbstract;
use PHPStan\Reflection\Native\NativeFunctionReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Type\CallableType;
use Rector\Contract\PhpParser\DecoratingNodeVisitorInterface;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Reflection\ReflectionResolver;

/**
 * Decorate method call, function call or static call, that accepts closure that
 * requires multiple args (variadic) - to handle them later in specific rules.
 */
final class ClosureWithVariadicParametersNodeVisitor extends NodeVisitorAbstract implements DecoratingNodeVisitorInterface
{
    public function __construct(
        private readonly ReflectionResolver $reflectionResolver,
    ) {
    }

    public function enterNode(Node $node): ?Node
    {
        if (! $node instanceof CallLike) {
            return null;
        }

        if ($node->isFirstClassCallable()) {
            return null;
        }

        $args = $node->getArgs();
        if ($args === []) {
            return null;
        }

        $filteredArgs = array_filter(
            $args,
            fn (Arg $arg): bool => $arg->value instanceof Closure || $arg->value instanceof ArrowFunction
        );

        if ($filteredArgs === []) {
            return null;
        }

        $methodReflection = $this->reflectionResolver->resolveFunctionLikeReflectionFromCall($node);

        foreach ($filteredArgs as $filteredArg) {
            if ($methodReflection instanceof NativeFunctionReflection) {
                $parametersAcceptors = ParametersAcceptorSelector::combineAcceptors(
                    $methodReflection->getVariants()
                );

                foreach ($parametersAcceptors->getParameters() as $extendedParameterReflection) {
                    if ($extendedParameterReflection->getType() instanceof CallableType && $extendedParameterReflection->getType()  ->isVariadic()) {
                        $filteredArg->value->setAttribute(AttributeKey::HAS_CLOSURE_WITH_VARIADIC_ARGS, true);
                    }
                }

                return null;
            }

            $filteredArg->value->setAttribute(AttributeKey::HAS_CLOSURE_WITH_VARIADIC_ARGS, true);
        }

        return null;
    }
}
