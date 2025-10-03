<?php

declare(strict_types=1);

namespace Rector\Contract\Rector;

use PhpParser\Node;
use PhpParser\NodeVisitor;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;

interface RectorInterface extends NodeVisitor, DocumentedRuleInterface
{
    /**
     * List of nodes this class checks, classes that implements \PhpParser\Node
     * See beautiful map of all nodes https://github.com/rectorphp/php-parser-nodes-docs#node-overview
     *
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array;

    /**
     * Process Node of matched type
     * @return Node|Node[]|null|NodeVisitor::*
     */
    public function refactor(Node $node);
}
