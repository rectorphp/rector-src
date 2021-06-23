<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\NodeTypeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ParameterReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\Type;
use PHPStan\Type\TypeWithClassName;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\NodeTypeResolver;

final class CallTypeAnalyzer
{
    public function __construct(
        private ReflectionProvider $reflectionProvider,
        private NodeNameResolver $nodeNameResolver,
        private NodeTypeResolver $nodeTypeResolver
    ) {
    }

    /**
     * @param MethodCall|StaticCall $node
     * @return Type[]
     */
    public function resolveMethodParameterTypes(Node $node): array
    {
        $callerType = $this->resolveCallerType($node);
        if (! $callerType instanceof TypeWithClassName) {
            return [];
        }

        $callerClassName = $callerType->getClassName();

        return $this->getMethodParameterTypes($callerClassName, $node);
    }

    /**
     * @param StaticCall|MethodCall $node
     */
    public function resolveCallerType(Node $node): Type
    {
        if ($node instanceof MethodCall) {
            return $this->nodeTypeResolver->getStaticType($node->var);
        }

        return $this->nodeTypeResolver->resolve($node->class);
    }

    /**
     * @param MethodCall|StaticCall $node
     * @return Type[]
     */
    private function getMethodParameterTypes(string $className, Node $node): array
    {
        $classReflection = $this->reflectionProvider->getClass($className);

        $methodName = $this->nodeNameResolver->getName($node->name);

        if (! $methodName) {
            return [];
        }

        $scope = $node->getAttribute(AttributeKey::SCOPE);
        if (! $scope instanceof Scope) {
            return [];
        }

        // method not found
        if (! $classReflection->hasMethod($methodName)) {
            return [];
        }

        $methodReflection = $classReflection->getMethod($methodName, $scope);
        $parametersAcceptor = ParametersAcceptorSelector::selectSingle($methodReflection->getVariants());

        $parameterTypes = [];

        /** @var ParameterReflection $parameterReflection */
        foreach ($parametersAcceptor->getParameters() as $parameterReflection) {
            $parameterTypes[] = $parameterReflection->getType();
        }

        return $parameterTypes;
    }
}
