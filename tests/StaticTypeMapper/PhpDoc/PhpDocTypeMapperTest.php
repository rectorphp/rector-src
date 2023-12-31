<?php

declare(strict_types=1);

namespace Rector\Tests\StaticTypeMapper\PhpDoc;

use Iterator;
use PhpParser\Node\Stmt\Nop;
use PHPStan\PhpDocParser\Ast\Type\ArrayShapeItemNode;
use PHPStan\PhpDocParser\Ast\Type\ArrayShapeNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\ArrayType;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\StaticTypeMapper\Naming\NameScopeFactory;
use Rector\StaticTypeMapper\PhpDoc\PhpDocTypeMapper;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class PhpDocTypeMapperTest extends AbstractLazyTestCase
{
    private PhpDocTypeMapper $phpDocTypeMapper;

    private NameScopeFactory $nameScopeFactory;

    protected function setUp(): void
    {
        $this->phpDocTypeMapper = $this->make(PhpDocTypeMapper::class);
        $this->nameScopeFactory = $this->make(NameScopeFactory::class);
    }

    /**
     * @param class-string $expectedPHPStanType
     */
    #[DataProvider('provideData')]
    public function test(TypeNode $typeNode, string $expectedPHPStanType): void
    {
        $nop = new Nop();
        $nameScope = $this->nameScopeFactory->createNameScopeFromNode($nop);

        $phpStanType = $this->phpDocTypeMapper->mapToPHPStanType($typeNode, $nop, $nameScope);
        $this->assertInstanceOf($expectedPHPStanType, $phpStanType);
    }

    /**
     * @return Iterator<class-string<ArrayType>[]|ArrayShapeNode[]>
     */
    public static function provideData(): Iterator
    {
        $arrayShapeNode = new ArrayShapeNode([new ArrayShapeItemNode(null, true, new IdentifierTypeNode('string'))]);

        yield [$arrayShapeNode, ArrayType::class];
    }
}
