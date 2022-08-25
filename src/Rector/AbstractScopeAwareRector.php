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
        /** @var MutatingScope|null $scope */
        $scope = $node->getAttribute(AttributeKey::SCOPE);

        if (! $scope instanceof MutatingScope) {
            $smartFileInfo = $this->file->getSmartFileInfo();
            $scope = $this->scopeAnalyzer->resolveScope($node, $smartFileInfo);
            $this->changedNodeScopeRefresher->refresh($node, $scope, $smartFileInfo);
        }

        return $this->refactorWithScope($node, $scope);
    }
}
