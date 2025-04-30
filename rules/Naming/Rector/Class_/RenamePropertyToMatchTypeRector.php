<?php

declare(strict_types=1);

namespace Rector\Naming\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Property;
use PHPStan\Type\ObjectType;
use Rector\Enum\ClassName;
use Rector\Naming\ExpectedNameResolver\MatchPropertyTypeExpectedNameResolver;
use Rector\Naming\PropertyRenamer\MatchTypePropertyRenamer;
use Rector\Naming\PropertyRenamer\PropertyPromotionRenamer;
use Rector\Naming\ValueObject\PropertyRename;
use Rector\Naming\ValueObjectFactory\PropertyRenameFactory;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\RenamePropertyToMatchTypeRectorTest
 */
final class RenamePropertyToMatchTypeRector extends AbstractRector
{
    private bool $hasChanged = false;

    public function __construct(
        private readonly MatchTypePropertyRenamer $matchTypePropertyRenamer,
        private readonly PropertyRenameFactory $propertyRenameFactory,
        private readonly MatchPropertyTypeExpectedNameResolver $matchPropertyTypeExpectedNameResolver,
        private readonly PropertyPromotionRenamer $propertyPromotionRenamer,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Rename property and method param to match its type',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @var EntityManager
     */
    private $eventManager;

    public function __construct(EntityManager $eventManager)
    {
        $this->eventManager = $eventManager;
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }
}
CODE_SAMPLE
                ),
            ]
        );
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
        $this->hasChanged = false;

        $this->refactorClassProperties($node);

        $hasPromotedPropertyChanged = $this->propertyPromotionRenamer->renamePropertyPromotion($node);
        if ($this->hasChanged) {
            return $node;
        }

        if ($hasPromotedPropertyChanged) {
            return $node;
        }

        return null;
    }

    private function refactorClassProperties(Class_ $classLike): void
    {
        foreach ($classLike->getProperties() as $property) {
            // skip public properties, as they can be used in external code
            if ($property->isPublic()) {
                continue;
            }

            if (!$classLike->isFinal() && $property->isProtected()) {
                continue;
            }

            $expectedPropertyName = $this->matchPropertyTypeExpectedNameResolver->resolve($property, $classLike);
            if ($expectedPropertyName === null) {
                continue;
            }

            $propertyRename = $this->propertyRenameFactory->createFromExpectedName(
                $classLike,
                $property,
                $expectedPropertyName
            );

            if (! $propertyRename instanceof PropertyRename) {
                continue;
            }

            if ($this->skipDateTimeOrMockObjectPropertyType($property)) {
                continue;
            }

            $renameProperty = $this->matchTypePropertyRenamer->rename($propertyRename);
            if (! $renameProperty instanceof Property) {
                continue;
            }

            $this->hasChanged = true;
        }
    }

    /**
     * Such properties can have "xMock" names that are not compatible with "MockObject" suffix
     * They should be kept and handled by another naming rule that deals with mocks
     */
    private function skipDateTimeOrMockObjectPropertyType(Property $property): bool
    {
        if (! $property->type instanceof Name) {
            return false;
        }

        if ($this->isObjectType($property->type, new ObjectType(ClassName::MOCK_OBJECT))) {
            return true;
        }

        return $this->isObjectType($property->type, new ObjectType(ClassName::DATE_TIME_INTERFACE));
    }
}
