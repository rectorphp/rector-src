<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\TypeMapper;

use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PHPStan\PhpDocParser\Ast\Type\CallableTypeParameterNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Reflection\ParameterReflection;
use PHPStan\Type\ClosureType;
use PHPStan\Type\Type;
use Rector\BetterPhpDocParser\ValueObject\Type\SpacingAwareCallableTypeNode;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\PHPStanStaticTypeMapper\PHPStanStaticTypeMapper;
use Symfony\Contracts\Service\Attribute\Required;
use Webmozart\Assert\Assert;

/**
 * @implements TypeMapperInterface<ClosureType>
 */
final class ClosureTypeMapper implements TypeMapperInterface
{
    private PHPStanStaticTypeMapper $phpStanStaticTypeMapper;

    /**
     * @return class-string<Type>
     */
    public function getNodeClass(): string
    {
        return ClosureType::class;
    }

    /**
     * @param ClosureType $type
     */
    public function mapToPHPStanPhpDocTypeNode(Type $type, string $typeKind): TypeNode
    {
        $identifierTypeNode = new IdentifierTypeNode($type->getClassName());

        $returnDocTypeNode = $this->phpStanStaticTypeMapper->mapToPHPStanPhpDocTypeNode(
            $type->getReturnType(),
            $typeKind
        );

        $callableTypeParameterNodes = [];
        foreach ($type->getParameters() as $parameterReflection) {
            /** @var ParameterReflection $parameterReflection */
            $typeNode = $this->phpStanStaticTypeMapper->mapToPHPStanPhpDocTypeNode(
                $parameterReflection->getType(),
                $typeKind
            );

            $callableTypeParameterNodes[] = new CallableTypeParameterNode(
                $typeNode,
                $parameterReflection->passedByReference()->yes(),
                $parameterReflection->isVariadic(),
                $parameterReflection->getName(),
                $parameterReflection->isOptional()
            );
        }

        // callable parameters must be of specific type
        Assert::allIsInstanceOf($callableTypeParameterNodes, CallableTypeParameterNode::class);

        return new SpacingAwareCallableTypeNode($identifierTypeNode, $callableTypeParameterNodes, $returnDocTypeNode);
    }

    /**
     * @param TypeKind::* $typeKind
     * @param ClosureType $type
     */
    public function mapToPhpParserNode(Type $type, string $typeKind): ?Node
    {
        if ($typeKind === TypeKind::PROPERTY) {
            return null;
        }

        return new FullyQualified('Closure');
    }

    #[Required]
    public function autowire(PHPStanStaticTypeMapper $phpStanStaticTypeMapper): void
    {
        $this->phpStanStaticTypeMapper = $phpStanStaticTypeMapper;
    }
}
