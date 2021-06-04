<?php

declare(strict_types=1);

namespace Rector\DowngradePhp72\NodeAnalyzer;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\Type;
use Rector\NodeCollector\NodeCollector\NodeRepository;
use Rector\NodeNameResolver\NodeNameResolver;

final class ParentChildClassMethodTypeResolver
{
    public function __construct(
        private NativeTypeClassTreeResolver $nativeTypeClassTreeResolver,
        private NodeRepository $nodeRepository,
        private ReflectionProvider $reflectionProvider,
        private NodeNameResolver $nodeNameResolver
    ) {
    }

    /**
     * @return array<class-string, Type>
     * @param ClassReflection[] $ancestors
     */
    public function resolve(
        ClassReflection $classReflection,
        string $methodName,
        int $paramPosition,
        array $ancestors
    ): array {
        $parameterTypesByClassName = [];

        // include types of class scope in case of trait
        if ($classReflection->isTrait()) {
            $parameterTypesByInterfaceName = $this->resolveInterfaceTypeByClassName(
                $classReflection,
                $methodName,
                $paramPosition
            );
            $parameterTypesByClassName = array_merge($parameterTypesByClassName, $parameterTypesByInterfaceName);
        }

        foreach ($ancestors as $ancestorClassReflection) {
            if (! $ancestorClassReflection->hasMethod($methodName)) {
                continue;
            }

            $parameterType = $this->nativeTypeClassTreeResolver->resolveParameterReflectionType(
                $ancestorClassReflection,
                $methodName,
                $paramPosition
            );

            $parameterTypesByClassName[$ancestorClassReflection->getName()] = $parameterType;

            // collect other children
            if ($ancestorClassReflection->isInterface() || $ancestorClassReflection->isClass()) {
                $interfaceParameterTypesByClassName = $this->collectInterfaceImplenters(
                    $ancestorClassReflection,
                    $methodName,
                    $paramPosition
                );

                $parameterTypesByClassName = array_merge(
                    $parameterTypesByClassName,
                    $interfaceParameterTypesByClassName
                );
            }
        }

        return $parameterTypesByClassName;
    }

    /**
     * @return array<class-string, Type>
     */
    private function resolveInterfaceTypeByClassName(ClassReflection $classReflection, string $methodName, int $position): array
    {
        $typesByClassName = [];

        foreach ($classReflection->getInterfaces() as $interfaceClassReflection) {
            if (! $interfaceClassReflection->hasMethod($methodName)) {
                continue;
            }

            $parameterType = $this->nativeTypeClassTreeResolver->resolveParameterReflectionType(
                $interfaceClassReflection,
                $methodName,
                $position
            );

            $typesByClassName[$interfaceClassReflection->getName()] = $parameterType;
        }

        return $typesByClassName;
    }

    /**
     * @return array<class-string, Type>
     */
    private function collectInterfaceImplenters(
        ClassReflection $ancestorClassReflection,
        string $methodName,
        int $paramPosition
    ): array {
        $parameterTypesByClassName = [];

        $interfaceImplementerClassLikes = $this->nodeRepository->findClassesAndInterfacesByType(
            $ancestorClassReflection->getName()
        );

        foreach ($interfaceImplementerClassLikes as $interfaceImplementerClassLike) {
            $interfaceImplementerClassLikeName = $this->nodeNameResolver->getName($interfaceImplementerClassLike);
            if ($interfaceImplementerClassLikeName === null) {
                continue;
            }

            /** @var class-string $interfaceImplementerClassLikeName */
            if (! $this->reflectionProvider->hasClass($interfaceImplementerClassLikeName)) {
                continue;
            }

            $interfaceImplementerClassReflection = $this->reflectionProvider->getClass(
                $interfaceImplementerClassLikeName
            );
            $parameterType = $this->nativeTypeClassTreeResolver->resolveParameterReflectionType(
                $interfaceImplementerClassReflection,
                $methodName,
                $paramPosition
            );

            $parameterTypesByClassName[$interfaceImplementerClassLikeName] = $parameterType;
        }

        return $parameterTypesByClassName;
    }
}
