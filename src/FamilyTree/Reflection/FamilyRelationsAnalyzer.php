<?php

declare(strict_types=1);

namespace Rector\FamilyTree\Reflection;

use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Interface_;
use PHPStan\Broker\ClassNotFoundException;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Caching\Cache;
use Rector\Caching\Enum\CacheKey;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Reflection\BetterReflection\SourceLocatorProvider\DynamicSourceLocatorProvider;
use Rector\Util\Reflection\PrivatesAccessor;

final class FamilyRelationsAnalyzer
{
    public function __construct(
        private readonly ReflectionProvider $reflectionProvider,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly PrivatesAccessor $privatesAccessor,
        private readonly DynamicSourceLocatorProvider $dynamicSourceLocatorProvider,
        private readonly Cache $cache,
        private bool $hasClassNamesCached = false
    ) {
    }

    /**
     * @return ClassReflection[]
     */
    public function getChildrenOfClassReflection(ClassReflection $desiredClassReflection): array
    {
        if ($desiredClassReflection->isFinalByKeyword()) {
            return [];
        }

        $this->loadClasses();

        /** @var ClassReflection[] $classReflections */
        $classReflections = $this->privatesAccessor->getPrivateProperty($this->reflectionProvider, 'classes');
        $childrenClassReflections = [];

        foreach ($classReflections as $classReflection) {
            if (! $classReflection->isSubclassOf($desiredClassReflection->getName())) {
                continue;
            }

            $childrenClassReflections[] = $classReflection;
        }

        return $childrenClassReflections;
    }

    /**
     * @api
     * @return string[]
     */
    public function getClassLikeAncestorNames(Class_ | Interface_ | Name $classOrName): array
    {
        $ancestorNames = [];

        if ($classOrName instanceof Name) {
            $fullName = $this->nodeNameResolver->getName($classOrName);
            if (! $this->reflectionProvider->hasClass($fullName)) {
                return [];
            }

            $classReflection = $this->reflectionProvider->getClass($fullName);
            $ancestors = [...$classReflection->getParents(), ...$classReflection->getInterfaces()];

            return array_map(
                static fn (ClassReflection $classReflection): string => $classReflection->getName(),
                $ancestors
            );
        }

        if ($classOrName instanceof Interface_) {
            foreach ($classOrName->extends as $extendInterfaceName) {
                $ancestorNames[] = $this->nodeNameResolver->getName($extendInterfaceName);
                $ancestorNames = array_merge($ancestorNames, $this->getClassLikeAncestorNames($extendInterfaceName));
            }
        }

        if ($classOrName instanceof Class_) {
            if ($classOrName->extends instanceof Name) {
                $ancestorNames[] = $this->nodeNameResolver->getName($classOrName->extends);
                $ancestorNames = array_merge($ancestorNames, $this->getClassLikeAncestorNames($classOrName->extends));
            }

            foreach ($classOrName->implements as $implement) {
                $ancestorNames[] = $this->nodeNameResolver->getName($implement);
                $ancestorNames = array_merge($ancestorNames, $this->getClassLikeAncestorNames($implement));
            }
        }

        /** @var string[] $ancestorNames */
        return $ancestorNames;
    }

    private function loadClasses(): void
    {
        if ($this->hasClassNamesCached) {
            return;
        }

        $key = CacheKey::CLASSNAMES_HASH_KEY . '_' . $this->dynamicSourceLocatorProvider->getCacheClassNameKey();
        $classNamesCache = $this->cache->load($key, CacheKey::CLASSNAMES_HASH_KEY);

        if (is_string($classNamesCache)) {
            $classNamesCache = json_decode($classNamesCache);
            if (is_array($classNamesCache)) {
                foreach ($classNamesCache as $classNameCache) {
                    try {
                        $this->reflectionProvider->getClass($classNameCache);
                    } catch (ClassNotFoundException) {
                    }
                }

                return;
            }
        }

        $this->hasClassNamesCached = true;
    }
}
