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
        foreach ($this->typeMappers as $typeMapper) {
            if (! $this->doesTypeMatch($type, $typeMapper)) {
                continue;
            }

            return $typeMapper->mapToPHPStanPhpDocTypeNode($type);
        }

        throw new NotImplementedYetException(__METHOD__ . ' for ' . $type::class);
    }

    /**
     * @param TypeKind::* $typeKind
     */
    public function mapToPhpParserNode(Type $type, string $typeKind): Name|ComplexType|Identifier|null
    {
        foreach ($this->typeMappers as $typeMapper) {
            if (! $this->doesTypeMatch($type, $typeMapper)) {
                continue;
            }

            return $typeMapper->mapToPhpParserNode($type, $typeKind);
        }

        throw new NotImplementedYetException(__METHOD__ . ' for ' . $type::class);
    }

    /**
     * @param TypeMapperInterface<Type> $typeMapper
     */
    private function doesTypeMatch(Type $type, TypeMapperInterface $typeMapper): bool
    {
        return array_any($typeMapper->getNodeClasses(), fn (string $nodeClass): bool => $type instanceof $nodeClass);
    }
}
