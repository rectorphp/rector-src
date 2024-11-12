<?php

declare(strict_types=1);

namespace Rector\Rector;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PHPStan\Analyser\MutatingScope;
use PHPStan\Analyser\Scope;
use Rector\Contract\Rector\ScopeAwareRectorInterface;
use Rector\Exception\ShouldNotHappenException;
use Rector\NodeTypeResolver\Node\AttributeKey;

/**
 * @deprecated This class is deprecated, as too granular.
 * Use \Rector\Rector\AbstractRector instead with help of \Rector\PHPStan\ScopeFetcher
 */
abstract class AbstractScopeAwareRector extends AbstractRector implements ScopeAwareRectorInterface
{
    /**
     * Process Node of matched type with its PHPStan scope
     * @return Node|Node[]|null|NodeTraverser::*
     */
    public function refactor(Node $node): int|array|Node|null
    {
        /** @var MutatingScope|null $currentScope */
        $currentScope = $node->getAttribute(AttributeKey::SCOPE);

        if (! $currentScope instanceof Scope) {
            $errorMessage = sprintf(
                'Scope not available on "%s" node, but is required by a refactorWithScope() method of "%s" rule. Fix scope refresh on changed nodes first',
                $node::class,
                static::class,
            );

            throw new ShouldNotHappenException($errorMessage);
        }

        return $this->refactorWithScope($node, $currentScope);
    }
}
