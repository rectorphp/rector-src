<?php

declare(strict_types=1);

namespace Rector\Caching\Detector;

use PhpParser\Node\Stmt\ClassLike;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\ValueObject\Application\File;
use Throwable;

/**
 * Resolves the class-ancestry files a file relies on (parents, interfaces, traits).
 * A change in any of them can change this file's refactoring result, so they must
 * invalidate its cache entry - see https://github.com/rectorphp/rector-src/issues/8493
 *
 * @see \Rector\Tests\Caching\Detector\FileDependencyResolverTest
 */
final readonly class FileDependencyResolver
{
    public function __construct(
        private BetterNodeFinder $betterNodeFinder,
        private NodeNameResolver $nodeNameResolver,
        private ReflectionProvider $reflectionProvider,
    ) {
    }

    /**
     * @return string[] real paths of the ancestry files, excluding the file itself
     */
    public function resolveDependentFilePaths(File $file): array
    {
        $classLikes = $this->betterNodeFinder->findInstanceOf($file->getNewStmts(), ClassLike::class);
        if ($classLikes === []) {
            return [];
        }

        $dependentFilePaths = [];
        foreach ($classLikes as $classLike) {
            $className = $this->nodeNameResolver->getName($classLike);
            if ($className === null) {
                continue;
            }

            if (! $this->reflectionProvider->hasClass($className)) {
                continue;
            }

            $classReflection = $this->reflectionProvider->getClass($className);

            // best-effort: resolving ancestors forces full reflection (incl. docblock parsing) and may
            // throw on unresolvable or malformed ancestors; a miss here only skips a cache optimisation
            try {
                $ancestorReflections = $this->resolveAncestors($classReflection);
            } catch (Throwable) {
                continue;
            }

            foreach ($ancestorReflections as $ancestorReflection) {
                $fileName = $ancestorReflection->getFileName();
                if ($fileName === null) {
                    continue;
                }

                $dependentFilePaths[$this->normalizePath($fileName)] = true;
            }
        }

        unset($dependentFilePaths[$this->normalizePath($file->getFilePath())]);

        return array_keys($dependentFilePaths);
    }

    /**
     * @return ClassReflection[]
     */
    private function resolveAncestors(ClassReflection $classReflection): array
    {
        return [
            ...$classReflection->getParents(),
            ...$classReflection->getInterfaces(),
            ...$classReflection->getTraits(),
        ];
    }

    private function normalizePath(string $filePath): string
    {
        $realPath = realpath($filePath);

        return $realPath !== false ? $realPath : $filePath;
    }
}
