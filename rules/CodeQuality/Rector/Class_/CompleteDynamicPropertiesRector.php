<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use Rector\CodeQuality\NodeAnalyzer\ClassLikeAnalyzer;
use Rector\CodeQuality\NodeAnalyzer\LocalPropertyAnalyzer;
use Rector\CodeQuality\NodeFactory\MissingPropertiesFactory;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeAnalyzer\ClassAnalyzer;
use Rector\NodeAnalyzer\PropertyPresenceChecker;
use Rector\Php80\NodeAnalyzer\PhpAttributeAnalyzer;
use Rector\PostRector\ValueObject\PropertyMetadata;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://3v4l.org/GL6II
 * @changelog https://3v4l.org/eTrhZ
 * @changelog https://3v4l.org/C554W
 *
 * @see \Rector\Tests\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector\CompleteDynamicPropertiesRectorTest
 */
final class CompleteDynamicPropertiesRector extends AbstractRector
{
    public function __construct(
        private readonly MissingPropertiesFactory $missingPropertiesFactory,
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
class SomeClass
{
    /**
     * @var int
     */
    public $value;

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

        $propertiesToComplete = $this->resolvePropertiesToComplete($node, $fetchedLocalPropertyNameToTypes);
        if ($propertiesToComplete === []) {
            return null;
        }

        $propertiesToComplete = $this->filterOutExistingProperties($node, $classReflection, $propertiesToComplete);

        $newProperties = $this->missingPropertiesFactory->create(
            $fetchedLocalPropertyNameToTypes,
            $propertiesToComplete
        );

        if ($newProperties === []) {
            return null;
        }

        $node->stmts = array_merge($newProperties, $node->stmts);

        return $node;
    }

    private function shouldSkipClass(Class_ $class): bool
    {
        if ($this->classAnalyzer->isAnonymousClass($class)) {
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
     * @return string[]
     */
    private function resolvePropertiesToComplete(Class_ $class, array $fetchedLocalPropertyNameToTypes): array
    {
        $propertyNames = $this->classLikeAnalyzer->resolvePropertyNames($class);

        /** @var string[] $fetchedLocalPropertyNames */
        $fetchedLocalPropertyNames = array_keys($fetchedLocalPropertyNameToTypes);

        return array_diff($fetchedLocalPropertyNames, $propertyNames);
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
