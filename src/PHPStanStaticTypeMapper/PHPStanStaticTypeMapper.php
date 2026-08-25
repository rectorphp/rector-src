<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper;

use PhpParser\Node\ComplexType;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\Type;
use Rector\Exception\NotImplementedYetException;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Webmozart\Assert\Assert;

final readonly class PHPStanStaticTypeMapper
{
    /**
     * @param TypeMapperInterface[] $typeMappers
     */
    public function __construct(
        private array $typeMappers
    ) {
        Assert::notEmpty($typeMappers);
    }

    public function mapToPHPStanPhpDocTypeNode(Type $type): TypeNode
    {
        $typeMapper = $this->matchTypeMapper($type);
        if (! $typeMapper instanceof TypeMapperInterface) {
            throw new NotImplementedYetException(__METHOD__ . ' for ' . $type::class);
        }

        return $typeMapper->mapToPHPStanPhpDocTypeNode($type);
    }

    /**
     * @param TypeKind::* $typeKind
     */
    public function mapToPhpParserNode(Type $type, string $typeKind): Name|ComplexType|Identifier|null
    {
        $typeMapper = $this->matchTypeMapper($type);
        if (! $typeMapper instanceof TypeMapperInterface) {
            throw new NotImplementedYetException(__METHOD__ . ' for ' . $type::class);
        }

        return $typeMapper->mapToPhpParserNode($type, $typeKind);
    }

    /**
     * Match the most specific mapper: when a type is handled by both a mapper for a parent
     * class and one for its subclass, the subclass mapper wins, regardless of registration order.
     *
     * @return TypeMapperInterface<Type>|null
     */
    private function matchTypeMapper(Type $type): ?TypeMapperInterface
    {
        $matchedTypeMapper = null;
        $matchedNodeClass = null;

        foreach ($this->typeMappers as $typeMapper) {
            foreach ($typeMapper->getNodeClasses() as $nodeClass) {
                if (! $type instanceof $nodeClass) {
                    continue;
                }

                if ($matchedNodeClass === null || is_a($nodeClass, $matchedNodeClass, true)) {
                    $matchedNodeClass = $nodeClass;
                    $matchedTypeMapper = $typeMapper;
                }
            }
        }

        return $matchedTypeMapper;
    }
}
