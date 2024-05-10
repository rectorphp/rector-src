<?php

declare(strict_types=1);

namespace Rector\StaticTypeMapper\Mapper;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\Type;
use Rector\Exception\NotImplementedYetException;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\StaticTypeMapper\Contract\PhpParser\PhpParserNodeMapperInterface;

final readonly class PhpParserNodeMapper
{
    /**
     * @param PhpParserNodeMapperInterface[] $phpParserNodeMappers
     */
    public function __construct(
        private iterable $phpParserNodeMappers
    ) {
    }

    public function mapToPHPStanType(Node $node): Type
    {
        $nameOrExpr = $this->expandedNamespacedName($node);

        foreach ($this->phpParserNodeMappers as $phpParserNodeMapper) {
            if (! is_a($nameOrExpr, $phpParserNodeMapper->getNodeType())) {
                continue;
            }

            return $phpParserNodeMapper->mapToPHPStan($nameOrExpr);
        }

        throw new NotImplementedYetException($nameOrExpr::class);
    }

    private function expandedNamespacedName(Node $node): Node|FullyQualified
    {
        if ($node::class === Name::class && $node->hasAttribute(AttributeKey::NAMESPACED_NAME)) {
            return new FullyQualified($node->getAttribute(AttributeKey::NAMESPACED_NAME));
        }

        return $node;
    }
}
