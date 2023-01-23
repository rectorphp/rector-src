<?php

declare(strict_types=1);

namespace Rector\PostRector\Rector;

use PhpParser\Comment;
use PhpParser\Node;
use PhpParser\Node\Stmt\InlineHTML;
use PhpParser\Node\Stmt\Nop;
use Rector\BetterPhpDocParser\Comment\CommentsMerger;
use Rector\Core\PhpParser\Printer\BetterStandardPrinter;
use Rector\NodeRemoval\NodeRemover;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PostRector\Collector\NodesToAddCollector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * This class collects all to-be-added expresssions (= 1 line in code)
 * and then adds new expressions to list of $nodes
 *
 * From:
 * - $this->someCall();
 *
 * To:
 * - $this->someCall();
 * - $value = this->someNewCall(); // added expression
 */
final class NodeAddingPostRector extends AbstractPostRector
{
    public function __construct(
        private readonly NodesToAddCollector $nodesToAddCollector,
        private readonly BetterStandardPrinter $betterStandardPrinter,
        private readonly CommentsMerger $commentsMerger,
        private readonly NodeRemover $nodeRemover
    ) {
    }

    public function getPriority(): int
    {
        return 1000;
    }

    /**
     * @return array<int|string, Node>|Node
     */
    public function leaveNode(Node $node): array | Node
    {
        $newNodes = [$node];

        $nodesToAddAfter = $this->nodesToAddCollector->getNodesToAddAfterNode($node);
        if ($nodesToAddAfter !== []) {
            $this->nodesToAddCollector->clearNodesToAddAfter($node);
            $newNodes = array_merge($newNodes, $nodesToAddAfter);

            $currentNodeToAddAfter = current($nodesToAddAfter);
            $firstNodeAfterNode = $node->getAttribute(AttributeKey::NEXT_NODE);

            if ($node instanceof Nop && $firstNodeAfterNode instanceof InlineHTML && ! $currentNodeToAddAfter instanceof InlineHTML) {
                $this->nodeRemover->removeNode($node);

                // mark node as comment
                $nopValue = $this->betterStandardPrinter->print($node);
                $currentComments = $currentNodeToAddAfter->getAttribute(AttributeKey::COMMENTS) ?? [];
                $currentNodeToAddAfter->setAttribute(
                    AttributeKey::COMMENTS,
                    array_merge([new Comment($nopValue)], $currentComments)
                );

                $firstNodeAfterNode->setAttribute(AttributeKey::ORIGINAL_NODE, null);
            }
        }

        $nodesToAddBefore = $this->nodesToAddCollector->getNodesToAddBeforeNode($node);
        if ($nodesToAddBefore !== []) {
            $this->nodesToAddCollector->clearNodesToAddBefore($node);
            $newNodes = array_merge($nodesToAddBefore, $newNodes);

            $firstNodePreviousNode = $node->getAttribute(AttributeKey::PREVIOUS_NODE);
            if ($firstNodePreviousNode instanceof InlineHTML && ! $node instanceof InlineHTML) {
                // re-print InlineHTML is safe
                $firstNodePreviousNode->setAttribute(AttributeKey::ORIGINAL_NODE, null);
            }
        }

        if ($newNodes === [$node]) {
            return $node;
        }

        return $newNodes;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add nodes on weird positions',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run($value)
    {
        return 1;
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run($value)
    {
        if ($value) {
            return 1;
        }
    }
}
CODE_SAMPLE
                ), ]
        );
    }
}
