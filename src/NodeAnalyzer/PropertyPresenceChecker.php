<?php

declare(strict_types=1);

namespace Rector\NodeAnalyzer;

use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use Rector\CodeQuality\ValueObject\DefinedPropertyWithType;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\Php80\NodeAnalyzer\PromotedPropertyResolver;
use Rector\PostRector\ValueObject\PropertyMetadata;

/**
 * Can be local property, parent property etc.
 */
final readonly class PropertyPresenceChecker
{
    public function __construct(
        private PromotedPropertyResolver $promotedPropertyResolver,
        private NodeNameResolver $nodeNameResolver,
    ) {
    }

    /**
     * Includes parent classes and traits
     */
    public function hasClassContextProperty(Class_ $class, DefinedPropertyWithType $definedPropertyWithType): bool
    {
        $propertyOrParam = $this->getClassContextProperty($class, $definedPropertyWithType);
        return $propertyOrParam !== null;
    }

    public function getClassContextProperty(
        Class_ $class,
        DefinedPropertyWithType|PropertyMetadata $definedPropertyWithType
    ): Property | Param | null {
        $className = $this->nodeNameResolver->getName($class);
        if ($className === null) {
            return null;
        }

        $property = $class->getProperty($definedPropertyWithType->getName());
        if ($property instanceof Property) {
            return $property;
        }

        $promotedPropertyParams = $this->promotedPropertyResolver->resolveFromClass($class);

        foreach ($promotedPropertyParams as $promotedPropertyParam) {
            if ($this->nodeNameResolver->isName($promotedPropertyParam, $definedPropertyWithType->getName())) {
                return $promotedPropertyParam;
            }
        }

        return null;
    }
}
