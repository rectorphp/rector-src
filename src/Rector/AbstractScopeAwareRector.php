<?php

declare(strict_types=1);

namespace Rector\Core\Rector;

use PhpParser\Node;
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
            $parent = $node->getAttribute(AttributeKey::PARENT_NODE);

            $errorMessage = sprintf(
                'Scope not available on "%s" node with parent node of "%s", but is required by a refactorWithScope() method of "%s" rule. Fix scope refresh on changed nodes first',
                $node::class,
                $parent instanceof Node ? $parent::class : null,
                static::class,
            );

            throw new ShouldNotHappenException($errorMessage);
        }

        return $this->refactorWithScope($node, $scope);
    }
}
