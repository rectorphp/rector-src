<?php

declare(strict_types=1);

namespace Rector\Php82\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Attribute;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use Rector\CodeQuality\NodeAnalyzer\ClassLikeAnalyzer;
use Rector\CodeQuality\NodeAnalyzer\LocalPropertyAnalyzer;
use Rector\NodeAnalyzer\ClassAnalyzer;
use Rector\NodeAnalyzer\PropertyPresenceChecker;
use Rector\Php80\NodeAnalyzer\PhpAttributeAnalyzer;
use Rector\PostRector\ValueObject\PropertyMetadata;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Php82\Rector\Class_\AddAllowDynamicPropertiesAttributeToClassMissingPropertiesRector\AddAllowDynamicPropertiesAttributeToClassMissingPropertiesRectorTest
 */
final class AddAllowDynamicPropertiesAttributeToClassMissingPropertiesRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly LocalPropertyAnalyzer $localPropertyAnalyzer,
        private readonly ClassLikeAnalyzer $classLikeAnalyzer,
        private readonly ReflectionProvider $reflectionProvider,
        private readonly ClassAnalyzer $classAnalyzer,
        private readonly PropertyPresenceChecker $propertyPresenceChecker,
        private readonly PhpAttributeAnalyzer $phpAttributeAnalyzer
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add missing dynamic properties', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function set()
    {
        $this->value = 5;
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
#[\AllowDynamicProperties]
class SomeClass
{
    public function set()
    {
        $this->value = 5;
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
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkipClass($node)) {
            return null;
        }

        $className = $this->getName($node);
        if ($className === null) {
            return null;
        }

        if (! $this->reflectionProvider->hasClass($className)) {
            return null;
        }

        $classReflection = $this->reflectionProvider->getClass($className);

        // special case for Laravel Collection macro magic
        $fetchedLocalPropertyNameToTypes = $this->localPropertyAnalyzer->resolveFetchedPropertiesToTypesFromClass(
            $node
        );

        if (! $this->hasMissingProperties($node, $classReflection, $fetchedLocalPropertyNameToTypes)) {
            return null;
        }

        $node->attrGroups[] = new AttributeGroup([new Attribute(new FullyQualified('AllowDynamicProperties'))]);

        return $node;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::ALLOW_DYNAMIC_PROPERTIES_ATTRIBUTE;
    }

    private function shouldSkipClass(Class_ $class): bool
    {
        if ($this->classAnalyzer->isAnonymousClass($class)) {
            return true;
        }

        if ($this->isObjectType($class, new ObjectType('\stdClass'))) {
            return true;
        }

        $className = (string) $this->nodeNameResolver->getName($class);
        if (! $this->reflectionProvider->hasClass($className)) {
            return true;
        }

        if ($this->phpAttributeAnalyzer->hasPhpAttribute($class, 'AllowDynamicProperties')) {
            return true;
        }

        $classReflection = $this->reflectionProvider->getClass($className);

        // properties are accessed via magic, nothing we can do
        if ($classReflection->hasMethod('__set')) {
            return true;
        }

        return $classReflection->hasMethod('__get');
    }

    /**
     * @param array<string, Type> $fetchedLocalPropertyNameToTypes
     */
    private function hasMissingProperties(
        Class_ $class,
        ClassReflection $classReflection,
        array $fetchedLocalPropertyNameToTypes
    ): bool {
        $propertyNames = $this->classLikeAnalyzer->resolvePropertyNames($class);

        /** @var string[] $fetchedLocalPropertyNames */
        $fetchedLocalPropertyNames = array_keys($fetchedLocalPropertyNameToTypes);

        $propertiesToComplete = array_diff($fetchedLocalPropertyNames, $propertyNames);

        $propertiesToComplete = $this->filterOutExistingProperties($class, $classReflection, $propertiesToComplete);

        return $propertiesToComplete !== [];
    }

    /**
     * @param string[] $propertiesToComplete
     * @return string[]
     */
    private function filterOutExistingProperties(
        Class_ $class,
        ClassReflection $classReflection,
        array $propertiesToComplete
    ): array {
        $missingPropertyNames = [];

        $className = $classReflection->getName();
        // remove other properties that are accessible from this scope
        foreach ($propertiesToComplete as $propertyToComplete) {
            if ($classReflection->hasProperty($propertyToComplete)) {
                continue;
            }

            $propertyMetadata = new PropertyMetadata($propertyToComplete, new ObjectType($className));

            $hasClassContextProperty = $this->propertyPresenceChecker->hasClassContextProperty(
                $class,
                $propertyMetadata
            );
            if ($hasClassContextProperty) {
                continue;
            }

            $missingPropertyNames[] = $propertyToComplete;
        }

        return $missingPropertyNames;
    }
}
