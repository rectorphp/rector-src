<?php

declare(strict_types=1);

namespace Rector\Core\Rector;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PHPStan\Analyser\MutatingScope;
use PHPStan\Analyser\Scope;
use Rector\Core\Contract\Rector\ScopeAwarePhpRectorInterface;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\NodeAnalyzer\ScopeAnalyzer;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractScopeAwareRector extends AbstractRector implements ScopeAwarePhpRectorInterface
{
    private ScopeAnalyzer $scopeAnalyzer;

    #[Required]
    public function autowireAbstractScopeAwareRector(ScopeAnalyzer $scopeAnalyzer): void
    {
        $this->scopeAnalyzer = $scopeAnalyzer;
    }

    /**
     * Process Node of matched type with its PHPStan scope
     * @return Node|Node[]|null|NodeTraverser::*
     */
    public function refactor(Node $node)
    {
        /** @var MutatingScope|null $currentScope */
        $currentScope = $node->getAttribute(AttributeKey::SCOPE);

        if (! $currentScope instanceof MutatingScope) {
            $currentScope = $this->scopeAnalyzer->resolveScope($node, $this->file->getFilePath(), $this->currentStmt);
        }

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
