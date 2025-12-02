<?php

declare(strict_types=1);

namespace Rector\PhpParser\NodeTraverser;

use PhpParser\Node;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Stmt\Class_;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor;
use PhpParser\NodeVisitorAbstract;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class SimpleNodeTraverser
{
    /**
     * @param Node[]|Node $nodesOrNode
     * @param AttributeKey::* $attributeKey
     */
    public static function decorateWithAttributeValue(array|Node $nodesOrNode, string $attributeKey, mixed $value): void
    {
        $callableNodeVisitor = new class($attributeKey, $value) extends NodeVisitorAbstract {
            public function __construct(
                private readonly string $attributeKey,
                private readonly mixed $value
            ) {
            }

            public function enterNode(Node $node): ?int
            {
                // avoid nested functions or classes
                if ($node instanceof Class_ || $node instanceof FunctionLike) {
                    return NodeVisitor::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
                }

                $node->setAttribute($this->attributeKey, $this->value);
                return null;
            }
        };

        $nodeTraverser = new NodeTraverser($callableNodeVisitor);

        $nodes = $nodesOrNode instanceof Node ? [$nodesOrNode] : $nodesOrNode;
        $nodeTraverser->traverse($nodes);
    }
}
