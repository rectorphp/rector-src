<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\TypeMapper;

use PHPStan\PhpDocParser\Ast\Node as AstNode;
use PHPStan\PhpDocParser\Ast\NodeTraverser;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\ClosureType;
use PHPStan\Type\Type;
use Rector\BetterPhpDocParser\ValueObject\Type\FullyQualifiedIdentifierTypeNode;
use Rector\PhpDocParser\PhpDocParser\PhpDocNodeVisitor\CallablePhpDocNodeVisitor;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;

/**
 * @implements TypeMapperInterface<ClosureType>
 */
final readonly class ClosureTypeMapper implements TypeMapperInterface
{
    /**
     * @return array<class-string<Type>>
     */
    public function getNodeClasses(): array
    {
        return [ClosureType::class];
    }

    /**
     * @param ClosureType $type
     */
    public function mapToPHPStanPhpDocTypeNode(Type $type): TypeNode
    {
        $typeNode = $type->toPhpDocNode();

        $phpDocNodeTraverser = new NodeTraverser([new CallablePhpDocNodeVisitor(
            static function (AstNode $astNode): ?FullyQualifiedIdentifierTypeNode {
                if (! $astNode instanceof IdentifierTypeNode) {
                    return null;
                }

                if ($astNode->name !== 'Closure') {
                    return null;
                }

                return new FullyQualifiedIdentifierTypeNode('Closure');
            }
        )]);
        $phpDocNodeTraverser->traverse([$typeNode]);

        return $typeNode;
    }
}
