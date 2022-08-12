<?php

declare(strict_types=1);

namespace Rector\Core\Rector;

use PhpParser\Node;
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
     * @return Node|Node[]|null
     */
    public function refactor(Node $node)
    {
        /** @var MutatingScope|null $scope */
        $scope = $node->getAttribute(AttributeKey::SCOPE);

        if (! $scope instanceof MutatingScope) {
            $smartFileInfo = $this->file->getSmartFileInfo();
            $scope = $this->scopeAnalyzer->resolveScope($node, $smartFileInfo);
        }

        if (! $scope instanceof Scope) {
            /**
             * @var Node $parentNode
             *
             * $parentNode is always a Node when $mutatingScope is null, as checked in previous
             *
             *      $this->scopeAnalyzer->resolveScope()
             *
             *  which verify if no parent and no scope, it resolve Scope from File
             */
            $parentNode = $node->getAttribute(AttributeKey::PARENT_NODE);

            $errorMessage = sprintf(
                'Scope not available on "%s" node with parent node of "%s", but is required by a refactorWithScope() method of "%s" rule. Fix scope refresh on changed nodes first',
                $node::class,
                $parentNode::class,
                static::class,
            );

            throw new ShouldNotHappenException($errorMessage);
        }

        return $this->refactorWithScope($node, $scope);
    }
}
