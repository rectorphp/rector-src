<?php

declare(strict_types=1);

namespace Rector\StaticTypeMapper\Mapper;

use PhpParser\Node;
use PHPStan\Type\Type;
use Rector\Exception\NotImplementedYetException;
use Rector\StaticTypeMapper\Contract\PhpParser\PhpParserNodeMapperInterface;

final readonly class PhpParserNodeMapper
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
        $matchedNodeMapper = null;
        $matchedNodeType = null;

        foreach ($this->phpParserNodeMappers as $phpParserNodeMapper) {
            $nodeType = $phpParserNodeMapper->getNodeType();
            if (! is_a($node, $nodeType)) {
                continue;
            }

            // pick the most specific mapper: a mapper for a child node wins over one for its
            // parent node, regardless of registration order
            if ($matchedNodeType === null || is_a($nodeType, $matchedNodeType, true)) {
                $matchedNodeType = $nodeType;
                $matchedNodeMapper = $phpParserNodeMapper;
            }
        }

        if (! $matchedNodeMapper instanceof PhpParserNodeMapperInterface) {
            throw new NotImplementedYetException($node::class);
        }

        return $matchedNodeMapper->mapToPHPStan($node);
    }
}
