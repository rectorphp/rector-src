<?php

declare(strict_types=1);

namespace Rector\PhpParser\NodeTraverser;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\NodeVisitor;
use Rector\Configuration\ConfigurationRuleFilter;
use Rector\Contract\Rector\RectorInterface;
use Rector\VersionBonding\PhpVersionedFilter;

/**
 * @see \Rector\Tests\PhpParser\NodeTraverser\RectorNodeTraverserTest
 */
final class RectorNodeTraverser extends AbstractImmutableNodeTraverser
{
    private bool $areNodeVisitorsPrepared = false;

    /**
     * @var array<class-string<Node>, NodeVisitor[]>
     */
    private array $visitorsPerNodeClass = [];

    /**
     * @param RectorInterface[] $rectors
     */
    public function __construct(
        private array $rectors,
        private readonly PhpVersionedFilter $phpVersionedFilter,
        private readonly ConfigurationRuleFilter $configurationRuleFilter,
    ) {
        parent::__construct();
    }

    /**
     * @param Stmt[] $nodes
     * @return Stmt[]
     */
    public function traverse(array $nodes): array
    {
        $this->prepareNodeVisitors();

        return parent::traverse($nodes);
    }

    /**
     * @param RectorInterface[] $rectors
     * @api used in tests to update the active rules
     */
    public function refreshPhpRectors(array $rectors): void
    {
        $this->rectors = $rectors;
        $this->visitors = [];
        $this->visitorsPerNodeClass = [];

        $this->areNodeVisitorsPrepared = false;
    }

    /**
     * @return NodeVisitor[]
     */
    public function getVisitorsForNode(Node $node): array
    {
        // only in tests
        if ($this->areNodeVisitorsPrepared === false) {
            $this->prepareNodeVisitors();
        }

        return $this->visitorsPerNodeClass[$node::class] ?? [];
    }

    /**
     * This must happen after $this->configuration is set after ProcessCommand::execute() is run, otherwise we get default false positives.
     *
     * This should be removed after https://github.com/rectorphp/rector/issues/5584 is resolved
     */
    private function prepareNodeVisitors(): void
    {
        if ($this->areNodeVisitorsPrepared) {
            return;
        }

        // filer out by version
        $this->visitors = $this->phpVersionedFilter->filter($this->rectors);

        // filter by configuration
        $this->visitors = $this->configurationRuleFilter->filter($this->visitors);

        // 1. get all node non-interface, non-abstract classes
        // 2. iterate through them
        /** @var RectorInterface $visitor */
        foreach ($this->visitors as $visitor) {
            foreach (\Rector\Bridge\PhpParser\NodeClassFinder::find() as $nodeClass) {
                foreach ($visitor->getNodeTypes() as $matchingNodeType) {
                    if (is_a($nodeClass, $matchingNodeType, true)) {
                        $this->visitorsPerNodeClass[$nodeClass][] = $visitor;
                    }
                }
            }
        }

        $this->areNodeVisitorsPrepared = true;
    }
}
