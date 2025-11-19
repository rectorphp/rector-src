<?php

declare(strict_types=1);

namespace Rector\PhpParser\NodeTraverser;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\NodeVisitor;
use Rector\Configuration\ConfigurationRuleFilter;
use Rector\Contract\PhpParser\Node\StmtsAwareInterface;
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
        $nodeClass = $node::class;

        if (! isset($this->visitorsPerNodeClass[$nodeClass])) {
            $this->visitorsPerNodeClass[$nodeClass] = [];

            /** @var RectorInterface $visitor */

            foreach ($this->visitors as $visitor) {
                foreach ($visitor->getNodeTypes() as $nodeType) {
                    // special case for StmtsAwareInterface
                    if ($nodeType === StmtsAwareInterface::class) {
                        if (property_exists($node, 'stmts')) {
                            // all stmts are node classes
                            foreach ([Stmt\If_::class, Stmt\Function_::class, Stmt\ClassMethod::class, Stmt\For_::class, Stmt\While_::class] as $stmtsAwareNodeClass) {
                                dump($stmtsAwareNodeClass);
                                dump($visitor::class);

                                $this->visitorsPerNodeClass[$stmtsAwareNodeClass][] = $visitor;
                            }
                            continue 2;
                        }
                    }

                    if (is_a($nodeClass, $nodeType, true)) {
                        $this->visitorsPerNodeClass[$nodeClass][] = $visitor;
                        continue 2;
                    }
                }
            }
        }

        return $this->visitorsPerNodeClass[$nodeClass];
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

        $this->areNodeVisitorsPrepared = true;
    }
}
