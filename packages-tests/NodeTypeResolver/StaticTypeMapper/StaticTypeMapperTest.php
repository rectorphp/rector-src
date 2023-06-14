<?php

declare(strict_types=1);

namespace Rector\Tests\NodeTypeResolver\StaticTypeMapper;

use Iterator;
use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\String_;
use PHPStan\PhpDocParser\Ast\Type\ArrayTypeNode;
use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\ClassStringType;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\IterableType;
use PHPStan\Type\MixedType;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\Testing\PHPUnit\AbstractTestCase;

final class StaticTypeMapperTest extends AbstractTestCase
{
    private StaticTypeMapper $staticTypeMapper;

    protected function setUp(): void
    {
        $this->boot();
        $this->staticTypeMapper = $this->getService(StaticTypeMapper::class);
    }

    #[DataProvider('provideDataForMapPHPStanPhpDocTypeNodeToPHPStanType')]
    public function testMapPHPStanPhpDocTypeNodeToPHPStanType(TypeNode $typeNode, string $expectedType): void
    {
        $string = new String_('hey');

        $phpStanType = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType($typeNode, $string);

        $this->assertInstanceOf($expectedType, $phpStanType);
    }

    public static function provideDataForMapPHPStanPhpDocTypeNodeToPHPStanType(): Iterator
    {
        $genericTypeNode = new GenericTypeNode(new IdentifierTypeNode('Traversable'), []);
        yield [$genericTypeNode, GenericObjectType::class];

        $genericTypeNode = new GenericTypeNode(new IdentifierTypeNode('iterable'), [
            new IdentifierTypeNode('string'),
        ]);

        yield [$genericTypeNode, IterableType::class];

        yield [new IdentifierTypeNode('mixed'), MixedType::class];
    }

    public function testMapPHPStanTypeToPHPStanPhpDocTypeNode(): void
    {
        $iterableType = new IterableType(new MixedType(), new ClassStringType());

        $phpStanDocTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($iterableType);
        $this->assertInstanceOf(ArrayTypeNode::class, $phpStanDocTypeNode);

        /** @var ArrayTypeNode $phpStanDocTypeNode */
        $this->assertInstanceOf(IdentifierTypeNode::class, $phpStanDocTypeNode->type);
    }

    public function testMixed(): void
    {
        $mixedType = new MixedType();

        $phpStanDocTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($mixedType);
        $this->assertInstanceOf(IdentifierTypeNode::class, $phpStanDocTypeNode);
    }

    /**
     * @param class-string $expectedType
     */
    #[DataProvider('provideDataForMapPhpParserNodePHPStanType')]
    public function testMapPhpParserNodePHPStanType(Node $node, string $expectedType): void
    {
        $phpStanType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($node);
        $this->assertInstanceOf($expectedType, $phpStanType);
    }

    public static function provideDataForMapPhpParserNodePHPStanType(): Iterator
    {
        yield [new Identifier('iterable'), IterableType::class];
    }
}
