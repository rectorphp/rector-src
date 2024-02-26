<?php

declare(strict_types=1);

namespace Rector\CodeQuality\NodeFactory;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\PropertyProperty;
use PHPStan\PhpDocParser\Ast\PhpDoc\PropertyTagValueNode;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\StaticTypeMapper\StaticTypeMapper;

final class TypedPropertyFactory
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
        $propertyProperty = new PropertyProperty($propertyName);
        $propertyTypeNode = $this->createPropertyTypeNode($propertyTagValueNode, $class);

        return new Property(Class_::MODIFIER_PRIVATE, [$propertyProperty], [], $propertyTypeNode);
    }

    public function createPropertyTypeNode(PropertyTagValueNode $propertyTagValueNode, Class_ $class): Node
    {
        $propertyType = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType(
            $propertyTagValueNode->type,
            $class
        );

        return $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($propertyType, TypeKind::PROPERTY);
    }
}
