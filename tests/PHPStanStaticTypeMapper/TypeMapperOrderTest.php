<?php

declare(strict_types=1);

namespace Rector\Tests\PHPStanStaticTypeMapper;

use PHPStan\Type\ClassStringType;
use PHPStan\Type\StringType;
use Rector\PHPStanStaticTypeMapper\PHPStanStaticTypeMapper;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class TypeMapperOrderTest extends AbstractLazyTestCase
{
    private PHPStanStaticTypeMapper $phpStanStaticTypeMapper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->phpStanStaticTypeMapper = $this->make(PHPStanStaticTypeMapper::class);
    }

    /**
     * The most specific mapper wins regardless of registration order: ClassStringType extends
     * StringType, so the ClassStringType mapper must match it over the StringType one.
     */
    public function testMostSpecificMapperWinsOverParent(): void
    {
        $classStringDocNode = $this->phpStanStaticTypeMapper->mapToPHPStanPhpDocTypeNode(new ClassStringType());
        $this->assertSame('class-string', (string) $classStringDocNode);
    }

    public function testParentMapperStillMatchesParentType(): void
    {
        $stringDocNode = $this->phpStanStaticTypeMapper->mapToPHPStanPhpDocTypeNode(new StringType());
        $this->assertSame('string', (string) $stringDocNode);
    }
}
