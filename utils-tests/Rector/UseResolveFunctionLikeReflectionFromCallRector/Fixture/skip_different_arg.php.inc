<?php

declare(strict_types=1);

namespace Rector\Utils\Tests\Rector\UseResolveFunctionLikeReflectionFromCallRector\Fixture;

use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use Rector\Reflection\ReflectionResolver;

final class SkipDifferentArg
{
    public function __construct(
        private readonly ReflectionResolver $reflectionResolver,
    ) {
    }

    public function run(MethodCall|StaticCall $node, MethodCall|StaticCall $node2): void
    {
        $methodReflection = $node instanceof MethodCall
            ? $this->reflectionResolver->resolveMethodReflectionFromMethodCall($node)
            : $this->reflectionResolver->resolveMethodReflectionFromStaticCall($node2);
    }
}
