<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration;

use Iterator;
use PHPStan\Type\ArrayType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\MixedType;
use PHPStan\Type\StringType;
use PHPStan\Type\UnionType;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\TypeDeclaration\TypeNormalizer;

final class TypeNormalizerTest extends AbstractLazyTestCase
{
    private TypeNormalizer $typeNormalizer;

    protected function setUp(): void
    {
        $this->typeNormalizer = $this->make(TypeNormalizer::class);
    }

    #[DataProvider('provideData')]
    public function testNormalizeArrayOfUnionToUnionArray(ArrayType $arrayType, string $expectedDocString): void
    {
        $this->markTestSkipped();

        $unionType = $this->typeNormalizer->normalizeArrayOfUnionToUnionArray($arrayType);
        $this->assertInstanceOf(UnionType::class, $unionType);
    }

    public static function provideData(): Iterator
    {
        $unionType = new UnionType([new StringType(), new IntegerType()]);
        $arrayType = new ArrayType(new MixedType(), $unionType);
        yield [$arrayType, 'int[]|string[]'];

        $arrayType = new ArrayType(new MixedType(), $unionType);
        $moreNestedArrayType = new ArrayType(new MixedType(), $arrayType);
        yield [$moreNestedArrayType, 'int[][]|string[][]'];

        $evenMoreNestedArrayType = new ArrayType(new MixedType(), $moreNestedArrayType);
        yield [$evenMoreNestedArrayType, 'int[][][]|string[][][]'];
    }
}
