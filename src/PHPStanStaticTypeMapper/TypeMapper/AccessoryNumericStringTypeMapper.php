<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\TypeMapper;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\Accessory\AccessoryNumericStringType;
use PHPStan\Type\Type;
use Rector\Php\PhpVersionProvider;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
use Rector\ValueObject\PhpVersionFeature;

/**
 * @implements TypeMapperInterface<AccessoryNumericStringType>
 */
final class AccessoryNumericStringTypeMapper implements TypeMapperInterface
{
    public function __construct(
        private readonly PhpVersionProvider $phpVersionProvider
    ) {
    }

    public function getNodeClass(): string
    {
        return AccessoryNumericStringType::class;
    }

    /**
     * @param AccessoryNumericStringType $type
     */
    public function mapToPHPStanPhpDocTypeNode(Type $type): TypeNode
    {
        return $type->toPhpDocNode();
    }

    /**
     * @param AccessoryNumericStringType $type
     */
    public function mapToPhpParserNode(Type $type, string $typeKind): ?Node
    {
        if (! $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::SCALAR_TYPES)) {
            return null;
        }

        return new Identifier('string');
    }
}
