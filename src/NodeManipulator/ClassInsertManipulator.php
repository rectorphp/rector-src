<?php

declare(strict_types=1);

namespace Rector\Core\NodeManipulator;

use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\TraitUse;
use PHPStan\Type\Type;
use Rector\Core\PhpParser\Node\NodeFactory;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PostRector\ValueObject\PropertyMetadata;

final class ClassInsertManipulator
{
    /**
     * @var array<class-string<Stmt>>
     */
    private const BEFORE_TRAIT_TYPES = [TraitUse::class, Property::class, ClassMethod::class];

    public function __construct(
        private readonly NodeFactory $nodeFactory,
    ) {
    }

    /**
     * @api
     */
    public function addAsFirstMethod(Class_ $class, Property | ClassConst | ClassMethod $stmt): void
    {
        $scope = $class->getAttribute(AttributeKey::SCOPE);
        $stmt->setAttribute(AttributeKey::SCOPE, $scope);

        if ($this->isSuccessToInsertBeforeFirstMethod($class, $stmt)) {
            return;
        }

        if ($this->isSuccessToInsertAfterLastProperty($class, $stmt)) {
            return;
        }

        $class->stmts[] = $stmt;
    }

    /**
     * @api
     * @param Property[] $properties
     */
    public function addPropertiesToClass(Class_ $class, array $properties): void
    {
        foreach ($properties as $property) {
            $this->addAsFirstMethod($class, $property);
        }
    }

    /**
     * @internal Use PropertyAdder service instead
     */
    public function addPropertyToClass(Class_ $class, string $name, ?Type $type): void
    {
        $existingProperty = $class->getProperty($name);
        if ($existingProperty instanceof Property) {
            return;
        }

        $property = $this->nodeFactory->createPrivatePropertyFromNameAndType($name, $type);
        $this->addAsFirstMethod($class, $property);
    }

    public function addInjectPropertyToClass(Class_ $class, PropertyMetadata $propertyMetadata): void
    {
        $existingProperty = $class->getProperty($propertyMetadata->getName());
        if ($existingProperty instanceof Property) {
            return;
        }

        $property = $this->nodeFactory->createPublicInjectPropertyFromNameAndType(
            $propertyMetadata->getName(),
            $propertyMetadata->getType()
        );
        $this->addAsFirstMethod($class, $property);
    }

    public function addAsFirstTrait(Class_ $class, string $traitName): void
    {
        $traitUse = new TraitUse([new FullyQualified($traitName)]);
        $this->addTraitUse($class, $traitUse);
    }

    /**
     * @param Stmt[] $stmts
     * @return Stmt[]
     */
    private function insertBefore(array $stmts, Stmt $stmt, int $key): array
    {
        array_splice($stmts, $key, 0, [$stmt]);

        return $stmts;
    }

    private function isSuccessToInsertBeforeFirstMethod(Class_ $class, ClassConst|ClassMethod|Property $stmt): bool
    {
        foreach ($class->stmts as $key => $classStmt) {
            if (! $classStmt instanceof ClassMethod) {
                continue;
            }

            $class->stmts = $this->insertBefore($class->stmts, $stmt, $key);

            return true;
        }

        return false;
    }

    private function isSuccessToInsertAfterLastProperty(Class_ $class, ClassConst|ClassMethod|Property $stmt): bool
    {
        $previousElement = null;
        foreach ($class->stmts as $key => $classStmt) {
            if ($previousElement instanceof Property && ! $classStmt instanceof Property) {
                $class->stmts = $this->insertBefore($class->stmts, $stmt, $key);

                return true;
            }

            $previousElement = $classStmt;
        }

        return false;
    }

    private function addTraitUse(Class_ $class, TraitUse $traitUse): void
    {
        foreach (self::BEFORE_TRAIT_TYPES as $type) {
            foreach ($class->stmts as $key => $classStmt) {
                if (! $classStmt instanceof $type) {
                    continue;
                }

                $class->stmts = $this->insertBefore($class->stmts, $traitUse, $key);

                return;
            }
        }

        $class->stmts[] = $traitUse;
    }
}
