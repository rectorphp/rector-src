<?php

declare(strict_types=1);

namespace Rector\Php80\Guard;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\MixedType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Core\PhpParser\AstResolver;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\NodeNameResolver\NodeNameResolver;

final class MakeClassMethodParamMixedTypedGuard
{
    public function __construct(
        private readonly ReflectionResolver $reflectionResolver,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly AstResolver $astResolver,
        private readonly PhpDocInfoFactory $phpDocInfoFactory
    ) {
    }

    public function isLegal(ClassMethod $classMethod, int $keyParam): bool
    {
        $classReflection = $this->reflectionResolver->resolveClassReflection($classMethod);

        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        $methodName = $this->nodeNameResolver->getName($classMethod);
        $parents = array_merge($classReflection->getParents(), $classReflection->getInterfaces());

        foreach ($parents as $parent) {
            $classMethod = $this->astResolver->resolveClassMethod($parent->getName(), $methodName);

            if (! $classMethod instanceof ClassMethod) {
                continue;
            }

            $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($classMethod);
            $param = $classMethod->params[$keyParam] ?? null;

            if ($param instanceof Param) {
                if ($param->type instanceof Identifier) {
                    return $param->type->name === 'mixed';
                }

                // different typed, mark type as illegal to make same type consistent
                if ($param->type instanceof Node) {
                    return false;
                }

                $paramName = (string) $this->nodeNameResolver->getName($param->var);
                $paramType = $phpDocInfo->getParamType($paramName);

                if (! $paramType instanceof MixedType) {
                    return false;
                }
            }
        }

        return true;
    }
}
