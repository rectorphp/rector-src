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
        $originalNode = $node->getAttribute(AttributeKey::ORIGINAL_NODE);
        $originalNode ??= $node;

        /** @var MutatingScope|null $currentScope */
        $currentScope = $originalNode->getAttribute(AttributeKey::SCOPE);

        if (! $currentScope instanceof MutatingScope) {
            $currentScope = $this->scopeAnalyzer->resolveScope($node, $this->file->getFilePath());
        }

        if (! $currentScope instanceof Scope) {
            /**
             * @var Node $parentNode
             *
             * $parentNode is always a Node when $mutatingScope is null, as checked in previous
             *
             *      $this->scopeAnalyzer->resolveScope()
             *
             *  which verify if no parent and no scope, it resolve Scope from File
             */
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
