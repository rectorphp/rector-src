<?php

declare(strict_types=1);

namespace Rector\Core\Rector;

use PhpParser\Node;
use PHPStan\Analyser\MutatingScope;
use Rector\Core\Contract\Rector\ScopeAwarePhpRectorInterface;
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
     * @return Node|Node[]|null
     */
    public function refactor(Node $node)
    {
        $originalNode = $node->getAttribute(AttributeKey::ORIGINAL_NODE);
        $originalNode ??= $node;

        /** @var MutatingScope|null $currentScope */
        $currentScope = $originalNode->getAttribute(AttributeKey::SCOPE);

        if (! $currentScope instanceof MutatingScope) {
            $currentScope = $this->scopeAnalyzer->resolveScope($node, $this->file->getFilePath());
            $this->changedNodeScopeRefresher->refresh($node, $currentScope, $this->file->getFilePath());
        }

        return $this->refactorWithScope($node, $currentScope);
    }
}
