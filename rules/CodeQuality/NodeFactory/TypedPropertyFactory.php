<?php

declare(strict_types=1);

namespace Rector\CodeQuality\NodeFactory;

use PhpParser\Modifiers;
use PhpParser\Node;
use PhpParser\Node\ComplexType;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\PropertyItem;
use PHPStan\PhpDocParser\Ast\PhpDoc\PropertyTagValueNode;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\StaticTypeMapper\StaticTypeMapper;

final readonly class TypedPropertyFactory
{
    public function __construct(
        private StaticTypeMapper $staticTypeMapper,
    ) {
    }

    public function createFromPropertyTagValueNode(
        PropertyTagValueNode $propertyTagValueNode,
        Class_ $class,
        string $propertyName
    ): Property {
        $propertyItem = new PropertyItem($propertyName);
        $propertyTypeNode = $this->createPropertyTypeNode($propertyTagValueNode, $class);

        return new Property(Modifiers::PRIVATE, [$propertyItem], [], $propertyTypeNode);
    }

    public function createPropertyTypeNode(
        PropertyTagValueNode $propertyTagValueNode,
        Class_ $class,
        bool $isNullable = true
    ): Name|ComplexType|Identifier|null {
        $propertyType = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType(
            $propertyTagValueNode->type,
            $class
        );

        $typeNode = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($propertyType, TypeKind::PROPERTY);

        if ($isNullable && ! $typeNode instanceof NullableType && ! $typeNode instanceof ComplexType && $typeNode instanceof Node) {
            return new NullableType($typeNode);
        }

        return $typeNode;
    }
}
