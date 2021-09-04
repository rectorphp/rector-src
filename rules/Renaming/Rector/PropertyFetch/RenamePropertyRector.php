<?php

declare(strict_types=1);

namespace Rector\Renaming\Rector\PropertyFetch;

use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\VarLikeIdentifier;
use PHPStan\Type\ThisType;
use PHPStan\Type\ObjectType;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Renaming\ValueObject\RenameProperty;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Core\PhpParser\AstResolver;
use Rector\Naming\ExpectedNameResolver\MatchPropertyTypeExpectedNameResolver;
use Rector\Naming\PropertyRenamer\MatchTypePropertyRenamer;
use Rector\Naming\ValueObject\PropertyRename;
use Rector\Naming\ValueObjectFactory\PropertyRenameFactory;

/**
 * @see \Rector\Tests\Renaming\Rector\PropertyFetch\RenamePropertyRector\RenamePropertyRectorTest
 */
final class RenamePropertyRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var string
     */
    public const RENAMED_PROPERTIES = 'old_to_new_property_by_types';

    /**
     * @var RenameProperty[]
     */
    private array $renamedProperties = [];

    public function __construct(private ReflectionProvider $reflectionProvider, private AstResolver $astResolver, private MatchPropertyTypeExpectedNameResolver $matchPropertyTypeExpectedNameResolver, private PropertyRenameFactory $propertyRenameFactory, private MatchTypePropertyRenamer $matchTypePropertyRenamer)
    {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Replaces defined old properties by new ones.', [
            new ConfiguredCodeSample(
                '$someObject->someOldProperty;',
                '$someObject->someNewProperty;',
                [
                    self::RENAMED_PROPERTIES => [
                        new RenameProperty('SomeClass', 'someOldProperty', 'someNewProperty'),
                    ],
                ]
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [PropertyFetch::class];
    }

    /**
     * @param PropertyFetch $node
     */
    public function refactor(Node $node): ?Node
    {
        $class = $node->getAttribute(AttributeKey::CLASS_NODE);
        foreach ($this->renamedProperties as $renamedProperty) {
            if (! $this->isObjectType($node->var, $renamedProperty->getObjectType())) {
                continue;
            }

            $oldProperty = $renamedProperty->getOldProperty();
            if (! $this->isName($node, $oldProperty)) {
                continue;
            }

            $nodeVarType = $this->nodeTypeResolver->resolve($node->var);
            if ($nodeVarType instanceof ThisType && $class instanceof ClassLike) {
                $this->processThisType($class, $oldProperty, $renamedProperty);
            }

            if ($nodeVarType instanceof ObjectType && $this->reflectionProvider->hasClass($nodeVarType->getClassName())) {
                $this->processObjectType($nodeVarType->getClassName(), $oldProperty, $renamedProperty);
            }

            $node->name = new Identifier($renamedProperty->getNewProperty());
            return $node;
        }

        return null;
    }

    private function processThisType(ClassLike $class, string $oldProperty, RenameProperty $renamedProperty): void
    {
        foreach ($class->getProperties() as $property) {
            $expectedPropertyName = $this->matchPropertyTypeExpectedNameResolver->resolve($property);
            if ($expectedPropertyName === null) {
                continue;
            }

            $propertyRename = $this->propertyRenameFactory->createFromExpectedName($property, $expectedPropertyName);
            if (! $propertyRename instanceof PropertyRename) {
                continue;
            }

            $this->matchTypePropertyRenamer->rename($propertyRename);
        }
    }

    private function processObjectType(string $className, string $oldProperty, RenameProperty $renamedProperty): void
    {
        $classReflection = $this->reflectionProvider->getClass($className);
        if ($classReflection->isBuiltIn()) {
            return;
        }

        $class = $this->astResolver->resolveClassFromName($className);
        if (! $class instanceof ClassLike) {
            return;
        }

        $this->processThisType($class, $oldProperty, $renamedProperty);
    }

    /**
     * @param array<string, RenameProperty[]> $configuration
     */
    public function configure(array $configuration): void
    {
        $renamedProperties = $configuration[self::RENAMED_PROPERTIES] ?? [];
        Assert::allIsInstanceOf($renamedProperties, RenameProperty::class);
        $this->renamedProperties = $renamedProperties;
    }
}
