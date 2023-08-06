<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\TypeExtension;

use Illuminate\Container\Container;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\Type;
use Rector\Utils\PHPStan\ClassConstFetchReturnTypeResolver;

final class ContainerMakeReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
    public function __construct(
        private readonly ClassConstFetchReturnTypeResolver $classConstFetchReturnTypeResolver
    ) {
    }

    public function getClass(): string
    {
        return Container::class;
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getName() === 'make';
    }

    public function getTypeFromMethodCall(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        Scope $scope
    ): Type {
        return $this->classConstFetchReturnTypeResolver->resolve($methodReflection, $methodCall);
    }
}
