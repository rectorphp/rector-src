<?php

declare(strict_types=1);

namespace Rector\Php85\NodeManipulator;

use PhpParser\Builder\Property;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\BetterReflection\Reflection\Adapter\ReflectionProperty;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\Php80\NodeAnalyzer\PhpAttributeAnalyzer;
use Rector\Php81\Enum\AttributeName;
use Rector\Php81\NodeManipulator\AttributeGroupNewLiner;
use Rector\PHPStan\ScopeFetcher;
use Rector\Privatization\NodeManipulator\VisibilityManipulator;
use Rector\ValueObject\Application\File;
use Rector\ValueObject\MethodName;
use Rector\ValueObject\Visibility;

final readonly class FinalPromotionManipulator
{
    /**
     * @var string
     */
    private const TAGNAME = 'final';

    public function __construct(
        private VisibilityManipulator $visibilityManipulator,
        private PhpAttributeAnalyzer $phpAttributeAnalyzer,
        private ReflectionProvider $reflectionProvider,
        private readonly DocBlockUpdater $docBlockUpdater,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
    ) {
    }

    public function process(Class_ $class): Class_|null
    {
        $scope = ScopeFetcher::fetch($class);
        if ($this->shouldSkip($class, $scope)) {
            return null;
        }

        $constructClassMethod = $class->getMethod(MethodName::CONSTRUCT);

        if (! $constructClassMethod instanceof ClassMethod) {
            return null;
        }

        foreach ($constructClassMethod->getParams() as $param) {
            $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($param);

            if (!$phpDocInfo->hasByName(self::TAGNAME)) {
                continue;
            }
            $this->visibilityManipulator->makeFinal($param);
            $phpDocInfo->removeByName(self::TAGNAME); echo $param->var->name;
            $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($param);
        }

        return $class;
    }

    /**
     * @return ClassReflection[]
     */
    private function resolveParentClassReflections(Scope $scope): array
    {
        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return [];
        }

        return $classReflection->getParents();
    }

    private function shouldSkip(Class_ $class, Scope $scope): bool
    {
        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return true;
        }

        if ($this->shouldSkipClass($class)) {
            return true;
        }

        $parents = $this->resolveParentClassReflections($scope);

        if ($class->isAnonymous()) {
            return true;
        }

        if ($class->isFinal()) {
            return true;
        }

        foreach ($parents as $parent) {
            if ($parent->isFinal()) {
                return true;
            }
        }

        $properties = $class->getProperties();

        if ($this->shouldSkipConsumeTraitProperty($class)) {
            return true;
        }

        $constructClassMethod = $class->getMethod(MethodName::CONSTRUCT);
        if (! $constructClassMethod instanceof ClassMethod) {
            // no __construct means no property promotion, skip if class has no property defined
            return $properties === [];
        }

        $params = $constructClassMethod->getParams();
        if ($params === []) {
            // no params means no property promotion, skip if class has no property defined
            return $properties === [];
        }

        return $this->shouldSkipParams($params);
    }

    private function shouldSkipConsumeTraitProperty(Class_ $class): bool
    {
        $traitUses = $class->getTraitUses();
        foreach ($traitUses as $traitUse) {
            foreach ($traitUse->traits as $trait) {
                $traitName = $trait->toString();

                // trait not autoloaded
                if (! $this->reflectionProvider->hasClass($traitName)) {
                    return true;
                }

                $traitClassReflection = $this->reflectionProvider->getClass($traitName);
                $nativeReflection = $traitClassReflection->getNativeReflection();

                if ($this->hasFinalProperty($nativeReflection->getProperties())) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param ReflectionProperty[] $properties
     */
    private function hasFinalProperty(array $properties): bool
    {
        foreach ($properties as $property) {
            if (! $property->isFinal()) {
                return true;
            }
        }

        return false;
    }

    private function shouldSkipClass(Class_ $class): bool
    {
        // need to have test fixture once feature added to  nikic/PHP-Parser
        if ($this->visibilityManipulator->hasVisibility($class, Visibility::FINAL)) {
            return true;
        }

        if ($this->phpAttributeAnalyzer->hasPhpAttribute($class, AttributeName::ALLOW_DYNAMIC_PROPERTIES)) {
            return true;
        }
        
        return $class->extends instanceof FullyQualified && ! $this->reflectionProvider->hasClass(
            $class->extends->toString()
        );
    }

    /**
     * @param Param[] $params
     */
    private function shouldSkipParams(array $params): bool
    {
        foreach ($params as $param) {
            // has non-final property promotion
            if ($this->visibilityManipulator->hasVisibility($param, Visibility::FINAL) && $param->isPromoted()) {
                return true;
            }

            // type is missing, invalid syntax
            if ($param->type === null) {
                return true;
            }
        }
        return false;
    }
}
