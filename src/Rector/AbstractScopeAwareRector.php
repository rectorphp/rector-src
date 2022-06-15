<?php

declare(strict_types=1);

namespace Rector\Core\Rector;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PHPStan\Analyser\Scope;
use Rector\Core\Contract\Rector\ScopeAwarePhpRectorInterface;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\NodeTypeResolver\Node\AttributeKey;

/**
 * @internal Currently in experimental testing for core Rector rules. So we can verify if this feature is useful or not.
 * Do not use outside in custom rules. Go for AbstractRector instead.
 */
abstract class AbstractScopeAwareRector extends AbstractRector implements ScopeAwarePhpRectorInterface
{
    /**
     * Process Node of matched type with its PHPStan scope
     * @return Node|Node[]|null
     */
    public function refactor(Node $node)
    {
        $scope = $node->getAttribute(AttributeKey::SCOPE);
        if (! $scope instanceof Scope) {
            $scope = $this->resolveScopeFromNearestParentNode($node, $scope);
            if (! $scope instanceof Scope) {
                $parent = $node->getAttribute(AttributeKey::PARENT_NODE);

                $errorMessage = sprintf(
                    'Scope not available on "%s" node with parent node of "%s", but is required by a refactorWithScope() method of "%s" rule. Fix scope refresh on changed nodes first',
                    $node::class,
                    $parent instanceof Node ? $parent::class : null,
                    static::class,
                );

                throw new ShouldNotHappenException($errorMessage);
            }
        }

        return $this->refactorWithScope($node, $scope);
    }

    private function isCurrentStmtBelowParentNode(Node $parentNode, ?Stmt $currentStmt): bool
    {
        return (bool) $this->betterNodeFinder->findFirst(
            $parentNode,
            fn (Node $subNode): bool => $subNode === $currentStmt
        );
    }

    private function resolveScopeFromNearestParentNode(Node $node, ?Scope $scope): ?Scope
    {
        $nearestScope = null;
        $parentNode = $node->getAttribute(AttributeKey::PARENT_NODE);
        $currentStmt = $this->betterNodeFinder->resolveCurrentStatement($node);

        /** @var Scope|null $nearestScope */
        while (! $nearestScope instanceof Scope) {
            if (! $parentNode instanceof Node) {
                break;
            }

            if ($parentNode !== $currentStmt && $this->isCurrentStmtBelowParentNode($parentNode, $currentStmt)) {
                return null;
            }

            $nearestScope = $parentNode->getAttribute(AttributeKey::SCOPE);
            if ($nearestScope instanceof Scope) {
                $scope = $nearestScope;
                break;
            }

            $parentNode = $parentNode->getAttribute(AttributeKey::PARENT_NODE);
        }

        if (! $scope instanceof Scope) {
            return null;
        }

        $parentNode = $node->getAttribute(AttributeKey::PARENT_NODE);
        while ($parentNode instanceof Node) {
            $parentNodeScope = $parentNode->getAttribute(AttributeKey::SCOPE);
            if ($parentNodeScope instanceof Scope) {
                break;
            }

            $parentNode->setAttribute(AttributeKey::SCOPE, $scope);
            $parentNode = $parentNode->getAttribute(AttributeKey::PARENT_NODE);
        }

        $node->setAttribute(AttributeKey::SCOPE, $scope);
        return $scope;
    }
}
