<?php

declare(strict_types=1);

namespace Rector\StaticTypeMapper\Mapper;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\Type;
use Rector\Core\Exception\NotImplementedYetException;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\StaticTypeMapper\Contract\PhpParser\PhpParserNodeMapperInterface;

final class PhpParserNodeMapper
{
    /**
     * @param PhpParserNodeMapperInterface[] $phpParserNodeMappers
     */
    public function __construct(
        private array $phpParserNodeMappers
    ) {
    }

    public function mapToPHPStanType(Node $node): Type
    {
        if ($node::class === Name::class) {
            $namespacedName = $node->getAttribute(AttributeKey::NAMESPACED_NAME);
            if ($namespacedName !== null) {
                $node = new FullyQualified($namespacedName);
            }
        }

        foreach ($this->phpParserNodeMappers as $phpParserNodeMapper) {
            if (! is_a($node, $phpParserNodeMapper->getNodeType())) {
                continue;
            }

            // do not let Expr collect all the types
            // note: can be solve later with priorities on mapper interface, making this last
            if ($phpParserNodeMapper->getNodeType() !== Expr::class) {
                return $phpParserNodeMapper->mapToPHPStan($node);
            }

            if (! $node instanceof String_) {
                return $phpParserNodeMapper->mapToPHPStan($node);
            }
        }

        throw new NotImplementedYetException($node::class);
    }
}
