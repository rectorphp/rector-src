<?php

declare(strict_types=1);

namespace Rector\Renaming\Helper;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\NodeVisitorAbstract;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Core\Configuration\RenamedClassesDataCollector;
use Rector\NodeNameResolver\NodeNameResolver;

final class RenameClassCallbackHandler extends NodeVisitorAbstract
{
    /**
     * @var array<callable(ClassLike, NodeNameResolver, ReflectionProvider): ?string>
     */
    private array $oldToNewClassCallbacks = [];

    public function __construct(
        private readonly RenamedClassesDataCollector $renamedClassesDataCollector,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly ReflectionProvider $reflectionProvider
    ) {
    }

    public function hasOldToNewClassCallbacks(): bool
    {
        return $this->oldToNewClassCallbacks !== [];
    }

    /**
     * @param array<callable(ClassLike, NodeNameResolver, ReflectionProvider): ?string> $oldToNewClassCallbacks
     */
    public function addOldToNewClassCallbacks(array $oldToNewClassCallbacks): void
    {
        $this->oldToNewClassCallbacks = [...$this->oldToNewClassCallbacks, ...$oldToNewClassCallbacks];
    }

    /**
     * @return array<string, string>
     */
    public function getOldToNewClassesFromNode(Node $node): array
    {
        if ($node instanceof ClassLike) {
            return $this->handleClassLike($node);
        }

        return [];
    }

    /**
     * @return array<string, string>
     */
    public function handleClassLike(ClassLike $node): array
    {
        $oldToNewClasses = [];
        $className = $node->name;
        if ($className === null) {
            return [];
        }

        foreach ($this->oldToNewClassCallbacks as $oldToNewClassCallback) {
            $newClassName = $oldToNewClassCallback($node, $this->nodeNameResolver, $this->reflectionProvider);
            if ($newClassName !== null) {
                $fullyQualifiedClassName = (string) $this->nodeNameResolver->getName($node);
                $this->renamedClassesDataCollector->addOldToNewClass($fullyQualifiedClassName, $newClassName);
                $oldToNewClasses[$fullyQualifiedClassName] = $newClassName;
            }
        }

        return $oldToNewClasses;
    }
}
