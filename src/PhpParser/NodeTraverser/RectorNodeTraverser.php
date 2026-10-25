<?php

declare(strict_types=1);

namespace Rector\PhpParser\NodeTraverser;

use LogicException;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\NodeVisitor;
use Rector\Application\RectorRegistry;
use Rector\Rector\RectorRunner;
use Rector\ValueObject\Application\File;

final class RectorNodeTraverser
{
    private bool $stopTraversal;

    public function __construct(
        private readonly RectorRunner $rectorRunner,
        private readonly RectorRegistry $rectorRegistry,
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

            $currentNodeVisitors = $this->rectorRegistry->forNodeAndFile($subNode, $file);

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

            $this->traverseNode($subNode, $file);
            // a descendant removal (e.g. an emptied method body) can make a Class_ rule
            // newly apply; re-offer the class once, now that its children are settled
            $node->{$name} = $this->reRunClassRectors($subNode, $file);
            $subNode = $node->{$name};

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

            $currentNodeVisitors = $this->rectorRegistry->forNodeAndFile($node, $file);

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

            $this->traverseNode($node, $file);

            // re-offer a Class_ after descendant removals settle (see traverseNode)
            $nodes[$i] = $node = $this->reRunClassRectors($node, $file);

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
     * Re-run Class_ rules once, after its children are traversed, so a rule whose
     * precondition is only met by a descendant removal still fires in a single pass.
     */
    private function reRunClassRectors(Node $node, File $file): Node
    {
        if (! $node instanceof Class_) {
            return $node;
        }

        foreach ($this->rectorRegistry->forNodeAndFile($node, $file) as $currentNodeVisitor) {
            $return = $this->rectorRunner->run($currentNodeVisitor, $node);
            if (! $return instanceof Node) {
                continue;
            }

            $this->ensureReplacementReasonable($node, $return);
            $node = $return;
        }

        return $node;
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
