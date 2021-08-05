<?php

declare(strict_types=1);

namespace Rector\NodeCollector\NodeCollector;

use PhpParser\Node\Stmt\Class_;
use PHPStan\Reflection\ReflectionProvider;
use Rector\NodeTypeResolver\Node\AttributeKey;

/**
 * This service contains all the parsed nodes. E.g. all the functions, method call, classes, static calls etc. It's
 * useful in case of context analysis, e.g. find all the usage of class method to detect, if the method is used.
 *
 * @deprecated
 */
final class NodeRepository
{
    public function __construct(
        private ParsedNodeCollector $parsedNodeCollector,
        private ReflectionProvider $reflectionProvider,
    ) {
    }

    /**
     * @param class-string $className
     * @return Class_[]
     * @deprecated Use static reflection instead
     */
    public function findChildrenOfClass(string $className): array
    {
        $childrenClasses = [];

        // @todo refactor to reflection
        foreach ($this->parsedNodeCollector->getClasses() as $classNode) {
            $currentClassName = $classNode->getAttribute(AttributeKey::CLASS_NAME);
            if ($currentClassName === null) {
                continue;
            }

            if (! $this->isChildOrEqualClassLike($className, $currentClassName)) {
                continue;
            }

            $childrenClasses[] = $classNode;
        }

        return $childrenClasses;
    }

    /**
     * @deprecated Use static reflection instead
     *
     * @param class-string $name
     */
    public function findClass(string $name): ?Class_
    {
        return $this->parsedNodeCollector->findClass($name);
    }

    private function isChildOrEqualClassLike(string $desiredClass, string $currentClassName): bool
    {
        if (! $this->reflectionProvider->hasClass($desiredClass)) {
            return false;
        }

        if (! $this->reflectionProvider->hasClass($currentClassName)) {
            return false;
        }

        $desiredClassReflection = $this->reflectionProvider->getClass($desiredClass);
        $currentClassReflection = $this->reflectionProvider->getClass($currentClassName);

        if (! $currentClassReflection->isSubclassOf($desiredClassReflection->getName())) {
            return false;
        }

        return $currentClassName !== $desiredClass;
    }
}
