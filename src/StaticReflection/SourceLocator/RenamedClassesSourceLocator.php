<?php

declare(strict_types=1);

namespace Rector\Core\StaticReflection\SourceLocator;

use PHPStan\BetterReflection\Identifier\Identifier;
use PHPStan\BetterReflection\Identifier\IdentifierType;
use PHPStan\BetterReflection\Reflection\Reflection;
use PHPStan\BetterReflection\Reflection\ReflectionClass;
use PHPStan\BetterReflection\Reflector\Reflector;
use PHPStan\BetterReflection\SourceLocator\Type\SourceLocator;

/**
 * Inspired from \PHPStan\BetterReflection\SourceLocator\Type\StringSourceLocator
 */
final class RenamedClassesSourceLocator implements SourceLocator
{
    private \Rector\Core\Configuration\RenamedClassesDataCollector $renamedClassesDataCollector;

    public function __construct(\Rector\Core\Configuration\RenamedClassesDataCollector $renamedClassesDataCollector)
    {
        $this->renamedClassesDataCollector = $renamedClassesDataCollector;
    }

    public function locateIdentifier(Reflector $reflector, Identifier $identifier): ?Reflection
    {
        foreach ($this->renamedClassesDataCollector->getOldToNewClasses() as $oldClass => $newClass) {
            if ($identifier->getName() !== $oldClass) {
                continue;
            }

            return ReflectionClass::createFromName($newClass);
        }

        return null;
    }

    public function locateIdentifiersByType(Reflector $reflector, IdentifierType $identifierType): array
    {
        return [];
    }
}
