<?php

declare(strict_types=1);

namespace Rector\Php82\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PHPStan\Analyser\Scope;
use PHPStan\BetterReflection\Reflection\Adapter\ReflectionProperty;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Core\NodeAnalyzer\ClassAnalyzer;
use Rector\Core\Rector\AbstractScopeAwareRector;
use Rector\Core\ValueObject\MethodName;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\Core\ValueObject\Visibility;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Php80\NodeAnalyzer\PhpAttributeAnalyzer;
use Rector\Php81\Enum\AttributeName;
use Rector\Privatization\NodeManipulator\VisibilityManipulator;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://wiki.php.net/rfc/readonly_classes
 *
 * @see \Rector\Tests\Php82\Rector\Class_\ReadOnlyClassRector\ReadOnlyClassRectorTest
 */
final class ReadOnlyClassRector extends AbstractScopeAwareRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly ClassAnalyzer $classAnalyzer,
        private readonly VisibilityManipulator $visibilityManipulator,
        private readonly PhpAttributeAnalyzer $phpAttributeAnalyzer,
        private readonly ReflectionProvider $reflectionProvider
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Decorate read-only class with `readonly` attribute', [
            new CodeSample(
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function __construct(
        private readonly string $name
    ) {
    }
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
final readonly class SomeClass
{
    public function __construct(
        private string $name
    ) {
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactorWithScope(Node $node, Scope $scope): ?Node
    {
        if ($this->shouldSkip($node, $scope)) {
            return null;
        }

        $this->visibilityManipulator->makeReadonly($node);

        $constructClassMethod = $node->getMethod(MethodName::CONSTRUCT);

        if ($constructClassMethod instanceof ClassMethod) {
            foreach ($constructClassMethod->getParams() as $param) {
                $this->visibilityManipulator->removeReadonly($param);
            }
        }

        foreach ($node->getProperties() as $property) {
            $this->visibilityManipulator->removeReadonly($property);
        }

        if ($node->attrGroups !== []) {
            // invoke reprint with correct readonly newline
            $node->setAttribute(AttributeKey::ORIGINAL_NODE, null);
        }

        return $node;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::READONLY_CLASS;
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

    /**
     * @param Property[] $properties
     */
    private function hasNonTypedProperty(array $properties): bool
    {
        foreach ($properties as $property) {
            // properties of readonly class must always have type
            if ($property->type === null) {
                return true;
            }
        }

        return false;
    }

    private function shouldSkip(Class_ $class, Scope $scope): bool
    {
        if ($this->shouldSkipClass($class)) {
            return true;
        }

        $parents = $this->resolveParentClassReflections($scope);
        if (! $class->isFinal()) {
            return ! $this->isExtendsReadonlyClass($parents);
        }

        foreach ($parents as $parent) {
            if (! $parent->isReadOnly()) {
                return true;
            }
        }

        $properties = $class->getProperties();
        if ($this->hasWritableProperty($properties)) {
            return true;
        }

        if ($this->hasNonTypedProperty($properties)) {
            return true;
        }

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

                if ($this->hasReadonlyProperty($nativeReflection->getProperties())) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param ReflectionProperty[] $properties
     */
    private function hasReadonlyProperty(array $properties): bool
    {
        foreach ($properties as $property) {
            if (! $property->isReadOnly()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param ClassReflection[] $parents
     */
    private function isExtendsReadonlyClass(array $parents): bool
    {
        foreach ($parents as $parent) {
            if ($parent->isReadOnly()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Property[] $properties
     */
    private function hasWritableProperty(array $properties): bool
    {
        foreach ($properties as $property) {
            if (! $property->isReadonly()) {
                return true;
            }
        }

        return false;
    }

    private function shouldSkipClass(Class_ $class): bool
    {
        // need to have test fixture once feature added to  nikic/PHP-Parser
        if ($this->visibilityManipulator->hasVisibility($class, Visibility::READONLY)) {
            return true;
        }

        if ($this->classAnalyzer->isAnonymousClass($class)) {
            return true;
        }

        return $this->phpAttributeAnalyzer->hasPhpAttribute($class, AttributeName::ALLOW_DYNAMIC_PROPERTIES);
    }

    /**
     * @param Param[] $params
     */
    private function shouldSkipParams(array $params): bool
    {
        foreach ($params as $param) {
            // has non-property promotion, skip
            if (! $this->visibilityManipulator->hasVisibility($param, Visibility::READONLY)) {
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
