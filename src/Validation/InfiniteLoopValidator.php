<?php

declare(strict_types=1);

namespace Rector\Core\Validation;

use PhpParser\Node;
use Rector\Core\PhpParser\Comparing\NodeComparator;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class InfiniteLoopValidator
{
    public function __construct(private readonly NodeComparator $nodeComparator)
    {
    }

    /**
     * @param Node|array<Node> $node
     */
    public function isValid(Node|array $node, Node $originalNode): bool
    {
        if ($this->nodeComparator->areNodesEqual($node, $originalNode)) {
            return false;
        }

        $createdByRule = $originalNode->getAttribute(AttributeKey::CREATED_BY_RULE) ?? [];
        return $createdByRule === [];
    }
}
