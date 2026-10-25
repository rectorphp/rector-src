<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\TypeMapper;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\Type;
use PHPStan\Type\TypeWithClassName;
use Rector\Php\PhpVersionProvider;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
use Rector\ValueObject\PhpVersion;

/**
 * @implements TypeMapperInterface<TypeWithClassName>
 */
final readonly class TypeWithClassNameTypeMapper implements TypeMapperInterface
{
    public function __construct(
        private PhpVersionProvider $phpVersionProvider
    ) {
    }

    /**
     * @return array<class-string<Type>>
     */
    public function getNodeClasses(): array
    {
        return [TypeWithClassName::class];
    }

    /**
     * @param TypeWithClassName $type
     */
    public function mapToPHPStanPhpDocTypeNode(Type $type): TypeNode
    {
        return $type->toPhpDocNode();
    }

    /**
     * @param TypeWithClassName $type
     */
    public function mapToPhpParserNode(Type $type, string $typeKind): ?Node
    {
        if (! $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersion::PHP_70)) {
            return null;
        }

        return new Identifier('string');
    }
}
