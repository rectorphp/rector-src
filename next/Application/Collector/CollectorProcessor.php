<?php

declare(strict_types=1);

namespace Rector\Application\Collector;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PHPStan\Collectors\CollectedData;
use PHPStan\Collectors\Registry;

final class CollectorProcessor
{
    private readonly NodeTraverser $nodeTraverser;

    private readonly CollectorNodeVisitor $collectorNodeVisitor;

    public function __construct(
        Registry $collectorRegistry
    ) {
        $nodeTraverser = new NodeTraverser();

        $this->collectorNodeVisitor = new CollectorNodeVisitor($collectorRegistry);
        $nodeTraverser->addVisitor($this->collectorNodeVisitor);

        $this->nodeTraverser = $nodeTraverser;
    }

    /**
     * @param Node[] $stmts
     * @return CollectedData[]
     */
    public function process(array $stmts): array
    {
        $this->nodeTraverser->traverse($stmts);
        return $this->collectorNodeVisitor->getCollectedData();
    }
}
