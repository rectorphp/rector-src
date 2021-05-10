<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\UnionType;
use Rector\Core\PhpParser\Comparing\NodeComparator;

final class TypeNodeUnwrapper
{
    public function __construct(
        private NodeComparator $nodeComparator
    ) {
    }

    /**
     * @param array<UnionType|NullableType|Name|Identifier> $typeNodes
     * @return array<Name|Identifier>
     */
    public function unwrapNullableUnionTypes(array $typeNodes): array
    {
        $unwrappedTypeNodes = [];

        foreach ($typeNodes as $typeNode) {
            if ($typeNode instanceof UnionType) {
                $unwrappedTypeNodes = array_merge($unwrappedTypeNodes, $typeNode->types);
            } elseif ($typeNode instanceof NullableType) {
                $unwrappedTypeNodes[] = $typeNode->type;
                $unwrappedTypeNodes[] = new Identifier('null');
            } else {
                $unwrappedTypeNodes[] = $typeNode;
            }
        }

        return $this->uniquateNodes($unwrappedTypeNodes);
    }

    /**
     * @param Node[] $nodes
     * @return Node[]
     */
    public function uniquateNodes(array $nodes): array
    {
        $uniqueNodes = [];
        foreach ($nodes as $node) {
            $uniqueHash = $this->nodeComparator->printWithoutComments($node);
            $uniqueNodes[$uniqueHash] = $node;
        }

        // reset keys from 0, for further compatibility
        return array_values($uniqueNodes);
    }
}
