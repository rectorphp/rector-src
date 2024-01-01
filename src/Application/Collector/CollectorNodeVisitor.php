<?php

declare(strict_types=1);

namespace Rector\Application\Collector;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\CollectedData;
use PHPStan\Collectors\Registry;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Throwable;

/**
 * @see Mimics https://github.com/phpstan/phpstan-src/commit/bccd1cd58e0d117ac8755ab228e338d966cac25a
 */
final class CollectorNodeVisitor extends NodeVisitorAbstract
{
    /**
     * @var CollectedData[]
     */
    private array $collectedDatas = [];

    public function __construct(
        private readonly Registry $collectorRegistry
    ) {
    }

    /**
     * @param Node[] $nodes
     */
    public function beforeTraverse(array $nodes): ?array
    {
        $this->collectedDatas = [];

        return null;
    }

    public function enterNode(Node $node)
    {
        $collectors = $this->collectorRegistry->getCollectors($node::class);

        /** @var Scope $scope */
        $scope = $node->getAttribute(AttributeKey::SCOPE);

        foreach ($collectors as $collector) {
            try {
                $collectedData = $collector->processNode($node, $scope);
            } catch (Throwable) {
                // nothing to collect
                continue;
            }

            // no data collected
            if ($collectedData === null) {
                continue;
            }

            $this->collectedDatas[] = new CollectedData($collectedData, $scope->getFile(), $collector::class);
        }

        return null;
    }

    /**
     * @return CollectedData[]
     */
    public function getCollectedData(): array
    {
        return $this->collectedDatas;
    }
}
