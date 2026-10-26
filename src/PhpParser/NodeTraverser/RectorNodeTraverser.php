<?php

declare(strict_types=1);

namespace Rector\PhpParser\NodeTraverser;

use LogicException;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt;
use PhpParser\NodeVisitor;
use Rector\Application\RectorRegistry;
use Rector\Exception\ShouldNotHappenException;
use Rector\PhpParser\Printer\BetterStandardPrinter;
use Rector\Rector\RectorRunner;
use Rector\ValueObject\Application\File;

final class RectorNodeTraverser
{
    /**
     * Safety cap on local re-visits of a single node, so a rule that never settles cannot loop forever.
     */
    private const int MAX_REVISITS = 50;

    private bool $stopTraversal;

    public function __construct(
        private readonly RectorRunner $rectorRunner,
        private readonly RectorRegistry $rectorRegistry,
        private readonly BetterStandardPrinter $betterStandardPrinter,
    ) {
    }

    /**
     * @param Node[] $nodes
     * @return Node[]
     */
    public function traverse(array $nodes, File $file): array
    {
        $this->stopTraversal = false;

        return $this->traverseArray($nodes, $file);
    }

    private function traverseNode(Node $node, File $file): void
    {
        foreach ($node->getSubNodeNames() as $name) {
            $subNode = $node->{$name};
            if (\is_array($subNode)) {
                $node->{$name} = $this->traverseArray($subNode, $file);
                if ($this->stopTraversal) {
                    break;
                }

                continue;
            }

            if (! $subNode instanceof Node) {
                continue;
            }

            // a single sub-node slot cannot hold a list or be removed
            $node->{$name} = $this->refactorNode($subNode, $file, false);

            if ($this->stopTraversal) {
                break;
            }
        }
    }

    /**
     * @param array<Node|null> $nodes The null can be in case of empty list(, , )
     * @return Node[]
     */
    private function traverseArray(array $nodes, File $file): array
    {
        $doNodes = [];
        foreach ($nodes as $i => $node) {
            if (! $node instanceof Node) {
                if (\is_array($node)) {
                    throw new LogicException('Invalid node structure: Contains nested arrays');
                }

                continue;
            }

            $result = $this->refactorNode($node, $file, true);

            if (\is_array($result)) {
                $doNodes[] = [$i, $result];
            } elseif ($result === NodeVisitor::REMOVE_NODE) {
                $doNodes[] = [$i, []];
            } else {
                $nodes[$i] = $result;
            }

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

    /**
     * Applies the node's rules and traverses into its children, then re-applies its rules while a
     * descendant keeps changing - so a rule that depends on a change made deeper in the tree (ordered
     * via OrderedRectorInterface, or a plain multi-pass chain) converges within a single file pass.
     *
     * Termination is structural: the loop stops once a round reports no change, or reports a change
     * that leaves the node's printed form identical (a rule that keeps returning the same result).
     *
     * @param bool $allowListReturn whether an array / REMOVE_NODE return is valid here (only for a
     *                              node that lives inside a list, not a single sub-node slot)
     * @return NodeVisitor::REMOVE_NODE|Node|Node[]
     */
    private function refactorNode(Node $node, File $file, bool $allowListReturn): int|Node|array
    {
        $lastPrint = null;
        $revisits = 0;

        while (true) {
            $changeCountBefore = $this->changeCount($file);
            $return = $this->applyRectors($node, $file);

            if (\is_array($return) || $return === NodeVisitor::REMOVE_NODE) {
                if (! $allowListReturn) {
                    throw new LogicException('RectorRunner::run() returned invalid value of type ' . gettype($return));
                }

                // a list / removal replaces the node in its parent list; its children are not traversed
                return $return;
            }

            $typeChanged = $return::class !== $node::class;
            $node = $return;

            if ($typeChanged) {
                // stop traversing as node type changed and visitors won't work
                return $node;
            }

            $this->traverseNode($node, $file);
            if ($this->stopTraversal) {
                return $node;
            }

            // no rule (here or deeper) reported a change this round: the subtree is stable
            if ($this->changeCount($file) === $changeCountBefore) {
                return $node;
            }

            // a change was reported; keep going only while it actually reshapes the node
            $printNow = $this->betterStandardPrinter->print($node);
            if ($printNow === $lastPrint) {
                return $node;
            }

            $lastPrint = $printNow;

            if (++$revisits >= self::MAX_REVISITS) {
                throw new ShouldNotHappenException(
                    sprintf('Node "%s" did not converge after %d re-visits', $node->getType(), $revisits)
                );
            }
        }
    }

    /**
     * Runs every rule subscribed to this node, in order. A rule returning a same-type node keeps the
     * remaining rules running on the new node; a type change, an array or REMOVE_NODE stops the chain
     * and is handed back to the caller.
     *
     * @return NodeVisitor::REMOVE_NODE|Node|Node[]
     */
    private function applyRectors(Node $node, File $file): int|Node|array
    {
        $currentNodeVisitors = $this->rectorRegistry->forNodeAndFile($node, $file);

        foreach ($currentNodeVisitors as $currentNodeVisitor) {
            $return = $this->rectorRunner->run($currentNodeVisitor, $node);
            if ($return === null) {
                continue;
            }

            if ($return instanceof Node) {
                $originalNodeClass = $node::class;
                $this->ensureReplacementReasonable($node, $return);
                $node = $return;

                if ($originalNodeClass !== $node::class) {
                    return $node;
                }

                continue;
            }

            if (\is_array($return) || $return === NodeVisitor::REMOVE_NODE) {
                return $return;
            }

            throw new LogicException('RectorRunner::run() returned invalid value of type ' . gettype($return));
        }

        return $node;
    }

    private function changeCount(File $file): int
    {
        return count($file->getRectorWithLineChanges());
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
}
