<?php

declare(strict_types=1);

namespace Rector\NodeNestingScope\NodeFinder;

use PhpParser\Node;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\NodeNestingScope\ValueObject\ControlStructure;

final class ScopeAwareNodeFinder
{
    private bool $isBreakingNodeFoundFirst = false;

    public function __construct(
        private BetterNodeFinder $betterNodeFinder
    ) {
    }

    /**
     * Find node based on $callable or null, when the nesting scope is broken
     * @param array<class-string<Node>> $allowedTypes
     */
    public function findParentType(Node $node, array $allowedTypes): ?Node
    {
        $callable = function (Node $node) use ($allowedTypes): bool {
            foreach ($allowedTypes as $allowedType) {
                if (! is_a($node, $allowedType)) {
                    continue;
                }

                return true;
            }

            return false;
        };

        return $this->findParent($node, $callable, $allowedTypes);
    }

    /**
     * Find node based on $callable or null, when the nesting scope is broken
     * @param array<class-string<Node>> $allowedTypes
     */
    public function findParent(Node $node, callable $callable, array $allowedTypes): ?Node
    {
        /** @var array<class-string<Node>> $parentNestingBreakTypes */
        $parentNestingBreakTypes = array_diff(ControlStructure::BREAKING_SCOPE_NODE_TYPES, $allowedTypes);

        $this->isBreakingNodeFoundFirst = false;

        $foundNode = $this->betterNodeFinder->findFirstPrevious($node, function (Node $node) use (
            $callable,
            $parentNestingBreakTypes
        ): bool {
            if ($callable($node)) {
                return true;
            }

            foreach ($parentNestingBreakTypes as $parentNestingBreakType) {
                if (! is_a($node, $parentNestingBreakType, true)) {
                    continue;
                }

                $this->isBreakingNodeFoundFirst = true;
                return true;
            }

            return false;
        });

        if ($this->isBreakingNodeFoundFirst) {
            return null;
        }

        return $foundNode;
    }
}
