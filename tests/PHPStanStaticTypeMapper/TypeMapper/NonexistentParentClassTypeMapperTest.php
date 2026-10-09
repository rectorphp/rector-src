<?php

declare(strict_types=1);

namespace Rector\Tests\PHPStanStaticTypeMapper\TypeMapper;

use PhpParser\Node\Name;
use PHPStan\Type\NonexistentParentClassType;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\PHPStanStaticTypeMapper\PHPStanStaticTypeMapper;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class NonexistentParentClassTypeMapperTest extends AbstractLazyTestCase
{
    private PHPStanStaticTypeMapper $phpStanStaticTypeMapper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->phpStanStaticTypeMapper = $this->make(PHPStanStaticTypeMapper::class);
    }

    public function testMapToPhpParserNode(): void
    {
        $node = $this->phpStanStaticTypeMapper->mapToPhpParserNode(
            new NonexistentParentClassType(),
            TypeKind::RETURN
        );

        $this->assertInstanceOf(Name::class, $node);
        $this->assertSame('parent', $node->toString());
    }

    public function testMapToPHPStanPhpDocTypeNode(): void
    {
        $typeNode = $this->phpStanStaticTypeMapper->mapToPHPStanPhpDocTypeNode(new NonexistentParentClassType());
        $this->assertSame('parent', (string) $typeNode);
    }
}
