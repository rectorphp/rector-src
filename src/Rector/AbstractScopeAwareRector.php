<?php

declare(strict_types=1);

namespace Rector\Rector;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use Rector\Contract\Rector\ScopeAwareRectorInterface;
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
        trigger_error(
            sprintf(
                'The `Rector\Rector\AbstractScopeAwareRector` is removed, use `Rector\Rector\AbstractRector` with `ScopeFetcher::fetch($node)` instead, see upgrading guide %s',
                'https://github.com/rectorphp/rector-src/blob/main/UPGRADING.md#1-abstractscopeawarerector-is-removed-use-abstractrector-instead',
            ),
            E_USER_DEPRECATED
        );

        $currentScope = $node->getAttribute(AttributeKey::SCOPE);

        // method called needs to trigger exception above
        // as refactor() is called via refactorWithScope()
        return $this->refactorWithScope($node, $currentScope);
    }
}
