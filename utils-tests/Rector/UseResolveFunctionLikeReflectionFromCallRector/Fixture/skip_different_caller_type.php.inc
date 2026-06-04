<?php

declare(strict_types=1);

namespace Rector\Utils\Tests\Rector\UseResolveFunctionLikeReflectionFromCallRector\Fixture;

use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use Rector\Utils\Tests\Rector\UseResolveFunctionLikeReflectionFromCallRector\Source\DifferentResolver;

final class SkipDifferentCallerType
{
    public function __construct(
        private readonly DifferentResolver $differentResolver,
    ) {
    }

    public function run(MethodCall|StaticCall $node): void
    {
        $methodReflection = $node instanceof MethodCall
            ? $this->differentResolver->resolveMethodReflectionFromMethodCall($node)
            : $this->differentResolver->resolveMethodReflectionFromStaticCall($node);
    }
}
