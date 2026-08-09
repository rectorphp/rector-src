<?php

declare(strict_types=1);

namespace Rector\Contract\Rector;

use PhpParser\Node;
use PhpParser\NodeVisitor;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;

/**
 * @template TNode of Node = Node
 */
interface RectorInterface extends NodeVisitor, DocumentedRuleInterface
{
    /**
     * List of nodes this class checks, classes that implements \PhpParser\Node
     * See beautiful map of all nodes https://github.com/rectorphp/php-parser-nodes-docs#node-overview
     *
     * @return list<class-string<TNode>>
     */
    public function getNodeTypes(): array;

    /**
     * Process Node of matched type
     * @param TNode $node
     * @return Node|Node[]|null|NodeVisitor::REMOVE_NODE
     */
    public function refactor(Node $node);
}
