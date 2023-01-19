<?php

declare(strict_types=1);

namespace Rector\Core\Contract\Rector;

use PhpParser\Node;
use PhpParser\NodeVisitor;

/**
 * @api
 * @phpstan-template TNodeType of Node
 */
interface PhpRectorInterface extends NodeVisitor, RectorInterface
{
    /**
     * List of nodes this class checks, classes that implements \PhpParser\Node
     * See beautiful map of all nodes https://github.com/rectorphp/php-parser-nodes-docs#node-overview
     *
     * @return array<class-string<TNodeType>>
     */
    public function getNodeTypes(): array;

    /**
     * Process Node of matched type
     * @phpstan-param TNodeType $node
     * @return Node|Node[]|null
     */
    public function refactor(Node $node);
}
