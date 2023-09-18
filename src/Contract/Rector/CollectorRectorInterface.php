<?php

declare(strict_types=1);

namespace Rector\Core\Contract\Rector;

use PhpParser\Node;
use PhpParser\NodeVisitor;
use PHPStan\Node\CollectedDataNode;

/**
 * @api
 */
interface CollectorRectorInterface extends NodeVisitor
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
     * @return Node|Node[]|null|NodeTraverser::*
     */
    public function refactor(Node $node, CollectedDataNode $collectedDataNode): null|array|Node|int;
}
