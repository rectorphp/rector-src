<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\NodeAnalyzer;

use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Reflection\ClassReflection;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\Php80\NodeAnalyzer\PhpAttributeAnalyzer;
use Symplify\Astral\Naming\SimpleNameResolver;

final class ControllerRenderMethodAnalyzer
{
    public function __construct(
        private readonly SimpleNameResolver $simpleNameResolver,
        private readonly PhpAttributeAnalyzer $phpAttributeAnalyzer,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly ReflectionResolver $reflectionResolver
    ) {
    }

    public function isRenderMethod(ClassMethod $classMethod): bool
    {
        // nette one?
        $classReflection = $this->reflectionResolver->resolveClassReflection($classMethod);
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        if ($this->isNetteRenderMethod($classReflection, $classMethod)) {
            return true;
        }

        return $this->isSymfonyRenderMethod($classReflection, $classMethod);
    }

    private function isNetteRenderMethod(ClassReflection $classReflection, ClassMethod $classMethod): bool
    {
        if (! $classReflection->isSubclassOf('Nette\Application\UI\Control')) {
            return false;
        }

        if (! $classMethod->isPublic()) {
            return false;
        }

        return $this->simpleNameResolver->isNames($classMethod->name, ['render*', 'handle*', 'action*']);
    }

    private function isSymfonyRenderMethod(ClassReflection $classReflection, ClassMethod $classMethod): bool
    {
        if (! $classReflection->isSubclassOf(
            'Symfony\Bundle\FrameworkBundle\Controller\Controller'
        ) && ! $classReflection->isSubclassOf('Symfony\Bundle\FrameworkBundle\Controller\AbstractController')) {
            return false;
        }

        if (! $classMethod->isPublic()) {
            return false;
        }

        if ($this->simpleNameResolver->isNames($classMethod->name, ['__invoke', '*action'])) {
            return true;
        }

        if ($this->phpAttributeAnalyzer->hasPhpAttribute($classMethod, 'Symfony\Component\Routing\Annotation\Route')) {
            return true;
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNode($classMethod);
        if (! $phpDocInfo instanceof PhpDocInfo) {
            return false;
        }

        return $phpDocInfo->hasByAnnotationClass('Symfony\Component\Routing\Annotation\Route');
    }
}
