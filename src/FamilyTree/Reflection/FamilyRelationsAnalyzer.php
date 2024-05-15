<?php

declare(strict_types=1);

namespace Rector\FamilyTree\Reflection;

use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Interface_;
use PHPStan\BetterReflection\Reflector\DefaultReflector;
use PHPStan\BetterReflection\SourceLocator\Type\AggregateSourceLocator;
use PHPStan\Broker\ClassNotFoundException;
use PHPStan\File\CouldNotReadFileException;
use PHPStan\Reflection\BetterReflection\SourceLocator\OptimizedSingleFileSourceLocator;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Caching\Cache;
use Rector\Caching\Enum\CacheKey;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Reflection\BetterReflection\SourceLocatorProvider\DynamicSourceLocatorProvider;
use Rector\Util\Reflection\PrivatesAccessor;
use Webmozart\Assert\Assert;

final class FamilyRelationsAnalyzer
{
    public function __construct(
        private readonly ReflectionProvider $reflectionProvider,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly PrivatesAccessor $privatesAccessor,
        private readonly DynamicSourceLocatorProvider $dynamicSourceLocatorProvider,
        private readonly Cache $cache,
        private bool $hasCachedClassNames = false
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

        $this->collectClasses($this->dynamicSourceLocatorProvider->provide());

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

    private function collectClasses(AggregateSourceLocator $aggregateSourceLocator): void
    {
        $sourceLocators = $this->privatesAccessor->getPrivateProperty($aggregateSourceLocator, 'sourceLocators');
        if ($sourceLocators === []) {
            return;
        }

        // no need to collect classes on single file, will auto collected
        if (count($sourceLocators) === 1 && $sourceLocators[0] instanceof OptimizedSingleFileSourceLocator) {
            return;
        }

        if ($this->hasCachedClassNames) {
            return;
        }

        $key = $this->dynamicSourceLocatorProvider->getCacheClassNameKey();
        $classNamesCache = $this->cache->load($key, CacheKey::CLASSNAMES_HASH_KEY);

        if ($classNamesCache === null) {
            $this->initClassNamesCache($aggregateSourceLocator, $key);
            return;
        }

        Assert::isArray($classNamesCache);

        // trigger collect "classes" on cached class names collection
        foreach ($classNamesCache as $classNameCache) {
            try {
                $this->reflectionProvider->getClass($classNameCache);
            } catch (ClassNotFoundException) {
            }
        }

        $this->hasCachedClassNames = true;
    }

    private function initClassNamesCache(AggregateSourceLocator $aggregateSourceLocator, string $key): void
    {
        $defaultReflector = new DefaultReflector($aggregateSourceLocator);
        $classNames = [];

        // trigger collect "classes" on get class on locate identifier
        try {
            $reflections = $defaultReflector->reflectAllClasses();
            foreach ($reflections as $reflection) {
                try {
                    $className = $reflection->getName();
                    $this->reflectionProvider->getClass($className);
                } catch (ClassNotFoundException) {
                    continue;
                }

                $classNames[] = $className;
            }
        } catch (CouldNotReadFileException) {
        }

        $this->cache->save($key, CacheKey::CLASSNAMES_HASH_KEY, $classNames);
    }
}
