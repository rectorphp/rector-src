<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\TypeAnalyzer;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\TypeWithClassName;
use Rector\NodeTypeResolver\NodeTypeResolver;

final class MethodTypeAnalyzer
{
    public function __construct(
        private NodeTypeResolver $nodeTypeResolver
    ) {
    }

    /**
     * @param class-string $expectedClass
     * @param non-empty-string $expectedMethod
     */
    public function isCallTo(MethodCall $methodCall, string $expectedClass, string $expectedMethod): bool
    {
        if (! $this->isMethodName($methodCall, $expectedMethod)) {
            return false;
        }

        return $this->isInstanceOf($methodCall->var, $expectedClass);
    }

    /**
     * @param non-empty-string $expectedName
     */
    private function isMethodName(MethodCall $methodCall, string $expectedName): bool
    {
        if ($methodCall->name instanceof Identifier) {
            $comparison = strcasecmp($methodCall->name->toString(), $expectedName);
            if ($comparison === 0) {
                return true;
            }
        }

        $type = $this->nodeTypeResolver->getType($methodCall->name);

        if ($type instanceof ConstantStringType) {
            $comparison = strcasecmp($type->getValue(), $expectedName);
            if ($comparison === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param class-string $expectedClass
     */
    private function isInstanceOf(Expr $expr, string $expectedClass): bool
    {
        $type = $this->nodeTypeResolver->getType($expr);
        if (! $type instanceof TypeWithClassName) {
            return false;
        }

        $comparison = strcasecmp($expectedClass, $type->getClassName());
        if ($comparison === 0) {
            return true;
        }

        return $type->getAncestorWithClassName($expectedClass) !== null;
    }
}
