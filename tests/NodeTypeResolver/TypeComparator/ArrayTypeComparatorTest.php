<?php

declare(strict_types=1);

namespace Rector\Tests\NodeTypeResolver\TypeComparator;

use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ArrayType;
use PHPStan\Type\ClassStringType;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\Constant\ConstantIntegerType;
use PHPStan\Type\Generic\GenericClassStringType;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\UnionType;
use Rector\NodeTypeResolver\TypeComparator\ArrayTypeComparator;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\Tests\NodeTypeResolver\TypeComparator\Source\SomeGenericTypeObject;

final class ArrayTypeComparatorTest extends AbstractLazyTestCase
{
    private ArrayTypeComparator $arrayTypeComparator;

    private ReflectionProvider $reflectionProvider;

    protected function setUp(): void
    {
        parent::setUp();

        $this->arrayTypeComparator = $this->make(ArrayTypeComparator::class);
        $this->reflectionProvider = $this->make(ReflectionProvider::class);
    }

    public function testClassStringSubtype(): void
    {
        $classStringKeysArrayType = new ArrayType(new StringType(), new ClassStringType());
        $stringArrayType = new ArrayType(new StringType(), new MixedType());

        $isSubtypeActual = $this->arrayTypeComparator->isSubtype($classStringKeysArrayType, $stringArrayType);
        $this->assertTrue($isSubtypeActual);
    }

    public function testGenericObjectType(): void
    {
        $someGenericTypeObjectClassReflection = $this->reflectionProvider->getClass(SomeGenericTypeObject::class);
        $objectType = new ObjectType(SomeGenericTypeObject::class, null, $someGenericTypeObjectClassReflection);
        $genericClassStringType = new GenericClassStringType($objectType);

        $constantArrayType = new ConstantArrayType(
            [new ConstantIntegerType(0)],
            [new UnionType([$genericClassStringType, $genericClassStringType])]
        );

        $stringArrayType = new ArrayType(new StringType(), new MixedType());

        $isSubtypeActual = $this->arrayTypeComparator->isSubtype($constantArrayType, $stringArrayType);
        $this->assertFalse($isSubtypeActual);
    }
}
