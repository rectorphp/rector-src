<?php

declare(strict_types=1);

namespace Rector\PhpParser\NodeTraverser;

use PhpParser\ErrorHandler\Collecting;
use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor;
use PhpParser\NodeVisitor\NameResolver;
use Rector\Contract\Rector\RectorInterface;
use Rector\VersionBonding\PhpVersionedFilter;

final class RectorNodeTraverser extends NodeTraverser
{
    private bool $areNodeVisitorsPrepared = false;

    private ?NameResolver $nameResolver = null;

    /**
     * @var array<class-string<Node>,RectorInterface[]>
     */
    private array $visitorsPerNodeClass = [];

    /**
     * @param RectorInterface[] $rectors
     */
    public function __construct(
        private array $rectors,
        private readonly PhpVersionedFilter $phpVersionedFilter
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
        $this->nameResolver = null;
    }

    /**
     * We return the list of visitors (rector rules) that can be applied to each node class
     * This list is cached so that we don't need to continually check if a rule can be applied to a node
     *
     * @return NodeVisitor[]
     */
    public function getVisitorsForNode(Node $node): array
    {
        $nodeClass = $node::class;
        if (! isset($this->visitorsPerNodeClass[$nodeClass])) {
            $this->visitorsPerNodeClass[$nodeClass] = [];
            if ($this->nameResolver instanceof NameResolver) {
                //Always add the nameResolver:
                $this->visitorsPerNodeClass[$nodeClass][] = $this->nameResolver;
            }

            foreach ($this->visitors as $visitor) {
                if ($visitor instanceof RectorInterface) {
                    foreach ($visitor->getNodeTypes() as $nodeType) {
                        if (is_a($nodeClass, $nodeType, true)) {
                            $this->visitorsPerNodeClass[$nodeClass][] = $visitor;
                            continue 2;
                        }
                    }
                }
            }
        }

        return $this->visitorsPerNodeClass[$nodeClass];
    }

    /**
     * This must happen after $this->configuration is set after ProcessCommand::execute() is run,
     * otherwise we get default false positives.
     *
     * This hack should be removed after https://github.com/rectorphp/rector/issues/5584 is resolved
     */
    private function prepareNodeVisitors(): void
    {
        if ($this->areNodeVisitorsPrepared) {
            return;
        }

        //The NameResolver must be added to `visitors` so that its `beforeTraverse` is called.
        // Only adding it to the result of `getVisitorsForNode` is not sufficient!
        $this->nameResolver ??= new NameResolver(
            new Collecting(), //ignore duplicate name errors
            [
                'replaceNodes' => false,
            ],
        );

        // filer out by version
        $this->visitors = [$this->nameResolver, ...$this->phpVersionedFilter->filter($this->rectors)];
        $this->areNodeVisitorsPrepared = true;
    }
}
