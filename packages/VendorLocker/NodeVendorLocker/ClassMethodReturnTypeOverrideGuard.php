<?php

declare(strict_types=1);

namespace Rector\VendorLocker\NodeVendorLocker;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\FunctionVariantWithPhpDocs;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Reflection\Php\PhpMethodReflection;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Rector\Core\FileSystem\FilePathHelper;
use Rector\Core\NodeAnalyzer\MagicClassMethodAnalyzer;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\FamilyTree\Reflection\FamilyRelationsAnalyzer;
use Rector\NodeTypeResolver\PHPStan\ParametersAcceptorSelectorVariantsWrapper;
use Rector\TypeDeclaration\TypeInferer\ReturnTypeInferer;
use Rector\VendorLocker\ParentClassMethodTypeOverrideGuard;

final class ClassMethodReturnTypeOverrideGuard
{
    public function __construct(
        private readonly FamilyRelationsAnalyzer $familyRelationsAnalyzer,
        private readonly ReflectionResolver $reflectionResolver,
        private readonly ReturnTypeInferer $returnTypeInferer,
        private readonly ParentClassMethodTypeOverrideGuard $parentClassMethodTypeOverrideGuard,
        private readonly FilePathHelper $filePathHelper,
        private readonly MagicClassMethodAnalyzer $magicClassMethodAnalyzer
    ) {
    }

    public function shouldSkipClassMethod(ClassMethod $classMethod, Scope $scope): bool
    {
        if ($this->magicClassMethodAnalyzer->isUnsafeOverridden($classMethod)) {
            return true;
        }

        $classReflection = $this->reflectionResolver->resolveClassReflection($classMethod);
        if (! $classReflection instanceof ClassReflection) {
            return true;
        }

        if (! $this->isReturnTypeChangeAllowed($classMethod, $scope)) {
            return true;
        }

        if ($classMethod->isFinal()) {
            return false;
        }

        $childrenClassReflections = $this->familyRelationsAnalyzer->getChildrenOfClassReflection($classReflection);
        if ($childrenClassReflections === []) {
            return false;
        }

        if ($classMethod->returnType instanceof Node) {
            return true;
        }

        $returnType = $this->returnTypeInferer->inferFunctionLike($classMethod);
        return $this->hasChildrenDifferentTypeClassMethod($classMethod, $childrenClassReflections, $returnType);
    }

    /**
     * @param ClassReflection[] $childrenClassReflections
     */
    private function hasChildrenDifferentTypeClassMethod(
        ClassMethod $classMethod,
        array $childrenClassReflections,
        Type $returnType
    ): bool {
        $methodName = $classMethod->name->toString();
        foreach ($childrenClassReflections as $childClassReflection) {
            $methodReflection = $childClassReflection->getNativeMethod($methodName);
            if (! $methodReflection instanceof PhpMethodReflection) {
                continue;
            }

            $parametersAcceptor = ParametersAcceptorSelector::combineAcceptors($methodReflection->getVariants());
            $childReturnType = $parametersAcceptor->getNativeReturnType();

            if (! $returnType->isSuperTypeOf($childReturnType)->yes()) {
                return true;
            }
        }

        return false;
    }

    private function isReturnTypeChangeAllowed(ClassMethod $classMethod, Scope $scope): bool
    {
        // make sure return type is not protected by parent contract
        $parentClassMethodReflection = $this->parentClassMethodTypeOverrideGuard->getParentClassMethod($classMethod);

        // nothing to check
        if (! $parentClassMethodReflection instanceof MethodReflection) {
            return ! $this->parentClassMethodTypeOverrideGuard->hasParentClassMethod($classMethod);
        }

        $parametersAcceptor = ParametersAcceptorSelectorVariantsWrapper::select(
            $parentClassMethodReflection,
            $classMethod,
            $scope
        );
        if ($parametersAcceptor instanceof FunctionVariantWithPhpDocs && ! $parametersAcceptor->getNativeReturnType() instanceof MixedType) {
            return false;
        }

        $classReflection = $parentClassMethodReflection->getDeclaringClass();
        $fileName = $classReflection->getFileName();

        // probably internal
        if ($fileName === null) {
            return false;
        }

        /*
         * Below verify that both current file name and parent file name is not in the /vendor/, if yes, then allowed.
         * This can happen when rector run into /vendor/ directory while child and parent both are there.
         *
         *  @see https://3v4l.org/Rc0RF#v8.0.13
         *
         *     - both in /vendor/ -> allowed
         *     - one of them in /vendor/ -> not allowed
         *     - both not in /vendor/ -> allowed
         */
        /** @var ClassReflection $currentClassReflection */
        $currentClassReflection = $this->reflectionResolver->resolveClassReflection($classMethod);
        /** @var string $currentFileName */
        $currentFileName = $currentClassReflection->getFileName();

        // child (current)
        $normalizedCurrentFileName = $this->filePathHelper->normalizePathAndSchema($currentFileName);
        $isCurrentInVendor = str_contains($normalizedCurrentFileName, '/vendor/');

        // parent
        $normalizedFileName = $this->filePathHelper->normalizePathAndSchema($fileName);
        $isParentInVendor = str_contains($normalizedFileName, '/vendor/');

        return ($isCurrentInVendor && $isParentInVendor) || (! $isCurrentInVendor && ! $isParentInVendor);
    }
}
