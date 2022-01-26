<?php

declare(strict_types=1);

namespace Rector\Renaming\Rector\PropertyFetch;

use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\VarLikeIdentifier;
use PHPStan\Type\ObjectType;
use PHPStan\Type\ThisType;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\Renaming\ValueObject\RenameProperty;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\Renaming\Rector\PropertyFetch\RenamePropertyRector\RenamePropertyRectorTest
 */
final class RenamePropertyRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @deprecated
     * @var string
     */
    public const RENAMED_PROPERTIES = 'old_to_new_property_by_types';

    /**
     * @var RenameProperty[]
     */
    private array $renamedProperties = [];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Replaces defined old properties by new ones.', [
            new ConfiguredCodeSample(
                '$someObject->someOldProperty;',
                '$someObject->someNewProperty;',
                [new RenameProperty('SomeClass', 'someOldProperty', 'someNewProperty')]
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [PropertyFetch::class, ClassLike::class];
    }

    /**
     * @param PropertyFetch|ClassLike $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof ClassLike) {
            return $this->processFromClassLike($node);
        }

        return $this->processFromPropertyFetch($node);
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        $renamedProperties = $configuration[self::RENAMED_PROPERTIES] ?? $configuration;
        Assert::allIsAOf($renamedProperties, RenameProperty::class);
        $this->renamedProperties = $renamedProperties;
    }

    private function processFromClassLike(ClassLike $classLike): ClassLike
    {
        foreach ($this->renamedProperties as $renamedProperty) {
            $this->renameProperty($classLike, $renamedProperty);
        }

        return $classLike;
    }

    private function renameProperty(ClassLike $classLike, RenameProperty $renameProperty): void
    {
        $classLikeName = (string) $this->nodeNameResolver->getName($classLike);
        $renamePropertyObjectType = $renameProperty->getObjectType();
        $className = $renamePropertyObjectType->getClassName();

        $classLikeNameObjectType = new ObjectType($classLikeName);
        $classNameObjectType = new ObjectType($className);

        $isSuperType = $classNameObjectType->isSuperTypeOf($classLikeNameObjectType)
            ->yes();
        if ($classLikeName !== $className && ! $isSuperType) {
            return;
        }

        $property = $classLike->getProperty($renameProperty->getOldProperty());
        if (! $property instanceof Property) {
            return;
        }

        $newProperty = $renameProperty->getNewProperty();
        $targetNewProperty = $classLike->getProperty($newProperty);
        if ($targetNewProperty instanceof Property) {
            return;
        }

        $property->props[0]->name = new VarLikeIdentifier($newProperty);
    }

    private function processFromPropertyFetch(PropertyFetch $propertyFetch): ?PropertyFetch
    {
        $class = $this->betterNodeFinder->findParentType($propertyFetch, Class_::class);

        foreach ($this->renamedProperties as $renamedProperty) {
            if (! $this->isObjectType($propertyFetch->var, $renamedProperty->getObjectType())) {
                continue;
            }

            $oldProperty = $renamedProperty->getOldProperty();
            if (! $this->isName($propertyFetch, $oldProperty)) {
                continue;
            }

            $nodeVarType = $this->nodeTypeResolver->getType($propertyFetch->var);
            if ($nodeVarType instanceof ThisType && $class instanceof ClassLike) {
                $this->renameProperty($class, $renamedProperty);
            }

            $propertyFetch->name = new Identifier($renamedProperty->getNewProperty());
            return $propertyFetch;
        }

        return null;
    }
}
