<?php

declare(strict_types=1);

namespace Rector\Tests\PHPStanStaticTypeMapper\TypeMapper;

use PHPStan\Type\NonexistentParentClassType;
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

    public function testMapToPHPStanPhpDocTypeNode(): void
    {
        $typeNode = $this->phpStanStaticTypeMapper->mapToPHPStanPhpDocTypeNode(new NonexistentParentClassType());
        $this->assertSame('parent', (string) $typeNode);
    }
}
