<?php

declare(strict_types=1);

namespace Rector\PostRector\Rector;

use PhpParser\Comment;
use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Stmt\InlineHTML;
use PhpParser\Node\Stmt\Nop;
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

            $stmt = current($nodesToAddAfter);
            $firstNodeAfterNode = $node->getAttribute(AttributeKey::NEXT_NODE);

            if ($node instanceof Nop && $firstNodeAfterNode instanceof InlineHTML && ! $stmt instanceof InlineHTML) {
                // mark node as comment
                $nopComments = [];

                foreach ($node->getComments() as $comment) {
                    if ($comment instanceof Doc) {
                        $nopComments[] = new Comment(
                            $comment->getText(),
                            $comment->getStartLine(),
                            $comment->getStartFilePos(),
                            $comment->getStartTokenPos(),
                            $comment->getEndLine(),
                            $comment->getEndFilePos(),
                            $comment->getEndTokenPos()
                        );
                        continue;
                    }

                    $nopComments[] = $comment;
                }

                $currentComments = $stmt->getComments();
                $stmt->setAttribute(AttributeKey::COMMENTS, array_merge($nopComments, $currentComments));

                $firstNodeAfterNode->setAttribute(AttributeKey::ORIGINAL_NODE, null);
                $this->nodeRemover->removeNode($node);
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
