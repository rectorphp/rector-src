<?php

declare(strict_types=1);

namespace Rector\PhpParser\NodeTraverser;

use LogicException;
use Nette\Utils\FileSystem;
use Nette\Utils\Json;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt;
use PhpParser\NodeTraverserInterface;
use PhpParser\NodeVisitor;
use Rector\Configuration\ConfigurationRuleFilter;
use Rector\Contract\Rector\RectorInterface;
use Rector\Exception\ShouldNotHappenException;
use Rector\Rector\RectorRunner;
use Rector\VersionBonding\ComposerPackageConstraintFilter;
use Rector\VersionBonding\PhpVersionedFilter;
use Webmozart\Assert\Assert;

/**
 *  Based on native NodeTraverser class, but heavily customized for Rector needs.
 *
 *  The main differences are:
 *  - no leaveNode(), the RectorRunner calls each rule's refactor() method on enter
 *  - cached visitors per node class for performance, e.g. when we find rules for Class_ node, they're cached for next time
 *  - immutability features, register Rector rules once, then use; no changes on the fly
 *
 * @see \Rector\Tests\PhpParser\NodeTraverser\RectorNodeTraverserTest
 * @internal No BC promise on this class, it might change any time.
 */
final class RectorNodeTraverser implements NodeTraverserInterface
{
    /**
     * @var RectorInterface[]
     */
    private array $visitors = [];

    private bool $stopTraversal;

    private bool $areNodeVisitorsPrepared = false;

    /**
     * @var array<class-string<Node>, RectorInterface[]>
     */
    private array $visitorsPerNodeClass = [];

    /**
     * Static precomputed node-class to rule map, shipped with the package.
     * @var array<class-string<Node>, array<class-string<RectorInterface>>>
     */
    private array $nodeRuleMap = [];

    /**
     * @var array<class-string<RectorInterface>, RectorInterface>
     */
    private array $visitorByClass = [];

    /**
     * @var array<class-string<RectorInterface>, int>
     */
    private array $visitorPositionByClass = [];

    /**
     * Active rules missing from the static map, e.g. third-party rules.
     * @var array<int, RectorInterface>
     */
    private array $unknownVisitors = [];

    /**
     * Shared across instances, the static map file is immutable.
     * @var array<class-string<Node>, array<class-string<RectorInterface>>>|null
     */
    private static ?array $cachedNodeRuleMap = null;

    /**
     * @param RectorInterface[] $rectors
     */
    public function __construct(
        private array $rectors,
        private readonly PhpVersionedFilter $phpVersionedFilter,
        private readonly ComposerPackageConstraintFilter $composerPackageConstraintFilter,
        private readonly ConfigurationRuleFilter $configurationRuleFilter,
        private readonly RectorRunner $rectorRunner,
    ) {
    }

    public function addVisitor(NodeVisitor $visitor): void
    {
        throw new ShouldNotHappenException('The immutable node traverser does not support adding visitors.');
    }

    public function removeVisitor(NodeVisitor $visitor): void
    {
        throw new ShouldNotHappenException('The immutable node traverser does not support removing visitors.');
    }

    /**
     * @param Node[] $nodes
     * @return Node[]
     */
    public function traverse(array $nodes): array
    {
        $this->prepareNodeVisitors();

        $this->stopTraversal = false;

        return $this->traverseArray($nodes);
    }

    /**
     * @param RectorInterface[] $rectors
     * @api used in tests to update the active rules
     *
     * @internal Used only in Rector core, not supported outside. Might change any time.
     */
    public function refreshPhpRectors(array $rectors): void
    {
        Assert::allIsInstanceOf($rectors, RectorInterface::class);

        $this->rectors = $rectors;
        $this->visitors = [];
        $this->visitorsPerNodeClass = [];

        $this->areNodeVisitorsPrepared = false;

        $this->prepareNodeVisitors();
    }

    /**
     * @return RectorInterface[]
     *
     * @api used in tests
     */
    public function getVisitorsForNode(Node $node): array
    {
        $nodeClass = $node::class;

        if (isset($this->visitorsPerNodeClass[$nodeClass])) {
            return $this->visitorsPerNodeClass[$nodeClass];
        }

        $visitorsByPosition = [];

        if (array_key_exists($nodeClass, $this->nodeRuleMap)) {
            // no rule subscribes to this node class and no third-party rules to check
            if ($this->nodeRuleMap[$nodeClass] === [] && $this->unknownVisitors === []) {
                return $this->visitorsPerNodeClass[$nodeClass] = [];
            }

            // O(1) lookup in the static map, then keep only the active rules
            foreach ($this->nodeRuleMap[$nodeClass] as $ruleClass) {
                if (isset($this->visitorByClass[$ruleClass])) {
                    $visitorsByPosition[$this->visitorPositionByClass[$ruleClass]] = $this->visitorByClass[$ruleClass];
                }
            }

            // resolve rules missing from the static map, e.g. third-party rules
            foreach ($this->unknownVisitors as $position => $visitor) {
                if ($this->isVisitorForNodeClass($visitor, $nodeClass)) {
                    $visitorsByPosition[$position] = $visitor;
                }
            }
        } else {
            // node class missing from the static map, resolve against all active rules
            foreach ($this->visitors as $position => $visitor) {
                if ($this->isVisitorForNodeClass($visitor, $nodeClass)) {
                    $visitorsByPosition[$position] = $visitor;
                }
            }
        }

        // keep the original rule registration order
        ksort($visitorsByPosition);

        return $this->visitorsPerNodeClass[$nodeClass] = array_values($visitorsByPosition);
    }

    /**
     * @param class-string<Node> $nodeClass
     */
    private function isVisitorForNodeClass(RectorInterface $rector, string $nodeClass): bool
    {
        return array_any($rector->getNodeTypes(), fn (string $nodeType): bool => is_a($nodeClass, $nodeType, true));
    }

    private function traverseNode(Node $node): void
    {
        foreach ($node->getSubNodeNames() as $name) {
            $subNode = $node->{$name};
            if (\is_array($subNode)) {
                $node->{$name} = $this->traverseArray($subNode);
                if ($this->stopTraversal) {
                    break;
                }

                continue;
            }

            if (! $subNode instanceof Node) {
                continue;
            }

            $currentNodeVisitors = $this->getVisitorsForNode($subNode);

            foreach ($currentNodeVisitors as $currentNodeVisitor) {
                $return = $this->rectorRunner->run($currentNodeVisitor, $subNode);
                if ($return === null) {
                    continue;
                }

                if ($return instanceof Node) {
                    $originalSubNodeClass = $subNode::class;

                    $this->ensureReplacementReasonable($subNode, $return);
                    $subNode = $return;
                    $node->{$name} = $return;

                    if ($originalSubNodeClass !== $subNode::class) {
                        // stop traversing as node type changed and visitors won't work
                        continue 2;
                    }
                } else {
                    throw new LogicException('RectorRunner::run() returned invalid value of type ' . gettype($return));
                }
            }

            $this->traverseNode($subNode);
            if ($this->stopTraversal) {
                break;
            }
        }
    }

    /**
     * @param array<Node|null> $nodes The null can be in case of empty list(, , )
     * @return Node[]
     */
    private function traverseArray(array $nodes): array
    {
        $doNodes = [];
        foreach ($nodes as $i => $node) {
            if (! $node instanceof Node) {
                if (\is_array($node)) {
                    throw new LogicException('Invalid node structure: Contains nested arrays');
                }

                continue;
            }

            $traverseChildren = true;
            $currentNodeVisitors = $this->getVisitorsForNode($node);

            foreach ($currentNodeVisitors as $currentNodeVisitor) {
                $return = $this->rectorRunner->run($currentNodeVisitor, $node);
                if ($return !== null) {
                    if ($return instanceof Node) {
                        $originalNodeNodeClass = $node::class;
                        $this->ensureReplacementReasonable($node, $return);
                        $nodes[$i] = $node = $return;

                        if ($originalNodeNodeClass !== $return::class) {
                            // stop traversing as node type changed and visitors won't work
                            continue 2;
                        }
                    } elseif (\is_array($return)) {
                        $doNodes[] = [$i, $return];
                        continue 2;
                    } elseif ($return === NodeVisitor::REMOVE_NODE) {
                        $doNodes[] = [$i, []];
                        continue 2;
                    } else {
                        throw new LogicException('RectorRunner::run() returned invalid value of type ' . gettype($return));
                    }
                }
            }

            $this->traverseNode($node);
            if ($this->stopTraversal) {
                break;
            }
        }

        if ($doNodes !== []) {
            while ([$i, $replace] = array_pop($doNodes)) {
                array_splice($nodes, $i, 1, $replace);
            }
        }

        return $nodes;
    }

    private function ensureReplacementReasonable(Node $old, Node $new): void
    {
        if ($old instanceof Stmt) {
            if ($new instanceof Expr) {
                throw new LogicException(
                    sprintf('Trying to replace statement (%s) ', $old->getType()) . sprintf(
                        'with expression (%s). Are you missing a ',
                        $new->getType()
                    ) . 'Stmt_Expression wrapper?'
                );
            }

            return;
        }

        if ($new instanceof Stmt) {
            throw new LogicException(
                sprintf('Trying to replace expression (%s) ', $old->getType()) . sprintf(
                    'with statement (%s)',
                    $new->getType()
                )
            );
        }
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

        // filter out by PHP version
        $this->visitors = $this->phpVersionedFilter->filter($this->rectors);

        // filter out by composer package constraint
        $this->visitors = $this->composerPackageConstraintFilter->filter($this->visitors);

        // filter by configuration
        $this->visitors = $this->configurationRuleFilter->filter($this->visitors);

        $this->prepareNodeRuleMap();

        $this->areNodeVisitorsPrepared = true;
    }

    private function prepareNodeRuleMap(): void
    {
        $this->nodeRuleMap = $this->loadNodeRuleMap();

        $knownRuleClasses = [];
        foreach ($this->nodeRuleMap as $ruleClasses) {
            foreach ($ruleClasses as $ruleClass) {
                $knownRuleClasses[$ruleClass] = true;
            }
        }

        $this->visitorByClass = [];
        $this->visitorPositionByClass = [];
        $this->unknownVisitors = [];

        foreach ($this->visitors as $position => $visitor) {
            $visitorClass = $visitor::class;

            $this->visitorByClass[$visitorClass] = $visitor;
            $this->visitorPositionByClass[$visitorClass] = $position;

            if (! isset($knownRuleClasses[$visitorClass])) {
                $this->unknownVisitors[$position] = $visitor;
            }
        }
    }

    /**
     * @return array<class-string<Node>, array<class-string<RectorInterface>>>
     */
    private function loadNodeRuleMap(): array
    {
        if (self::$cachedNodeRuleMap !== null) {
            return self::$cachedNodeRuleMap;
        }

        $nodeRuleMapFilePath = __DIR__ . '/../../../config/node-rule-map.json';
        if (! file_exists($nodeRuleMapFilePath)) {
            return self::$cachedNodeRuleMap = [];
        }

        $nodeRuleMap = Json::decode(FileSystem::read($nodeRuleMapFilePath), true);
        if (! is_array($nodeRuleMap)) {
            return self::$cachedNodeRuleMap = [];
        }

        /** @var array<class-string<Node>, array<class-string<RectorInterface>>> $nodeRuleMap */
        return self::$cachedNodeRuleMap = $nodeRuleMap;
    }
}
