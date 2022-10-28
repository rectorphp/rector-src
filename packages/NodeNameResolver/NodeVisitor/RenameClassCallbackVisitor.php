<?php

declare(strict_types=1);

namespace Rector\NodeNameResolver\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\NodeVisitorAbstract;
use Rector\Core\Configuration\RenamedClassesDataCollector;
use Rector\NodeNameResolver\NodeNameResolver;

final class RenameClassCallbackVisitor extends NodeVisitorAbstract
{
    /**
     * @var array<callable(ClassLike, NodeNameResolver): ?string> $oldToNewClassCallbacks
     */
    private array $oldToNewClassCallbacks = [];

    public function __construct(
        private readonly RenamedClassesDataCollector $renamedClassesDataCollector,
        private readonly NodeNameResolver $nodeNameResolver,
    ) {
    }

    /**
     * @param array<callable(ClassLike, NodeNameResolver): ?string> $oldToNewClassCallbacks
     */
    public function addOldToNewClassCallbacks(array $oldToNewClassCallbacks): void
    {
        $this->oldToNewClassCallbacks = [...$this->oldToNewClassCallbacks, ...$oldToNewClassCallbacks];
    }

    public function enterNode(Node $node): ?Node
    {
        if ($node instanceof ClassLike) {
            $className = $node->name;
            if ($className === null) {
                return $node;
            }

            foreach ($this->oldToNewClassCallbacks as $oldToNewClassCallback) {
                $newClassName = $oldToNewClassCallback($node, $this->nodeNameResolver);
                if ($newClassName !== null) {
                    $fullyQualifiedClassName = (string) $this->nodeNameResolver->getName($node);
                    $this->renamedClassesDataCollector->addOldToNewClass($fullyQualifiedClassName, $newClassName);
                }
            }
        }

        return $node;
    }
}
