<?php

declare(strict_types=1);

namespace Rector\CodeQuality\NodeAnalyzer;

use PhpParser\Node\Stmt\Class_;
use PHPStan\Reflection\ClassReflection;
use Rector\CodeQuality\ValueObject\DefinedPropertyWithType;
use Rector\NodeAnalyzer\PropertyPresenceChecker;

final readonly class MissingPropertiesResolver
{
    public function __construct(
        private ClassLikeAnalyzer $classLikeAnalyzer,
        private PropertyPresenceChecker $propertyPresenceChecker,
    ) {
    }

    /**
     * @param DefinedPropertyWithType[] $definedPropertiesWithTypes
     * @return DefinedPropertyWithType[]
     */
    public function resolve(Class_ $class, ClassReflection $classReflection, array $definedPropertiesWithTypes): array
    {
        $existingPropertyNames = $this->classLikeAnalyzer->resolvePropertyNames($class);

        $missingPropertiesWithTypes = [];

        foreach ($definedPropertiesWithTypes as $definedPropertyWithType) {
            // 1. property already exists, skip it
            if (in_array($definedPropertyWithType->getName(), $existingPropertyNames, true)) {
                continue;
            }

            // 2. is part of class docblock or another magic, skip it
            if ($classReflection->hasProperty($definedPropertyWithType->getName())) {
                continue;
            }

            // 3. is fetched by parent class on non-private property etc., skip it
            $hasClassContextProperty = $this->propertyPresenceChecker->hasClassContextProperty(
                $class,
                $definedPropertyWithType
            );

            if ($hasClassContextProperty) {
                continue;
            }

            // it's most likely missing!
            $missingPropertiesWithTypes[] = $definedPropertyWithType;
        }

        return $missingPropertiesWithTypes;
    }
}
