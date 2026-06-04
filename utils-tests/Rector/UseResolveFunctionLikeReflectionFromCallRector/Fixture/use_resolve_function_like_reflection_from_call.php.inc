<?php

declare(strict_types=1);

namespace Rector\Utils\Tests\Rector\UseResolveFunctionLikeReflectionFromCallRector\Fixture;

use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use Rector\Reflection\ReflectionResolver;

final class UseResolveFunctionLikeReflectionFromCall
{
    public function __construct(
        private readonly ReflectionResolver $reflectionResolver,
    ) {
    }

    public function run(MethodCall|StaticCall $node): void
    {
        $methodReflection = $node instanceof MethodCall
            ? $this->reflectionResolver->resolveMethodReflectionFromMethodCall($node)
            : $this->reflectionResolver->resolveMethodReflectionFromStaticCall($node);
    }

    public function run2(MethodCall|StaticCall $node): void
    {
        $methodReflection = $node instanceof StaticCall
            ? $this->reflectionResolver->resolveMethodReflectionFromStaticCall($node)
            : $this->reflectionResolver->resolveMethodReflectionFromMethodCall($node);
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Utils\Tests\Rector\UseResolveFunctionLikeReflectionFromCallRector\Fixture;

use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use Rector\Reflection\ReflectionResolver;

final class UseResolveFunctionLikeReflectionFromCall
{
    public function __construct(
        private readonly ReflectionResolver $reflectionResolver,
    ) {
    }

    public function run(MethodCall|StaticCall $node): void
    {
        $methodReflection = $this->reflectionResolver->resolveFunctionLikeReflectionFromCall($node);
    }

    public function run2(MethodCall|StaticCall $node): void
    {
        $methodReflection = $this->reflectionResolver->resolveFunctionLikeReflectionFromCall($node);
    }
}

?>
