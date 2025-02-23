<?php

declare(strict_types=1);

namespace Rector\Tests\NodeTypeResolver\TypeComparator;

use Iterator;
use PHPStan\Type\BooleanType;
use PHPStan\Type\ClassStringType;
use PHPStan\Type\IntegerRangeType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\NodeTypeResolver\TypeComparator\ScalarTypeComparator;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class ScalarTypeComparatorTest extends AbstractLazyTestCase
{
    private ScalarTypeComparator $scalarTypeComparator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->scalarTypeComparator = $this->make(ScalarTypeComparator::class);
    }

    #[DataProvider('provideData')]
    public function test(Type $firstType, Type $secondType, bool $areExpectedEqual): void
    {
        $areEqual = $this->scalarTypeComparator->areEqualScalar($firstType, $secondType);
        $this->assertSame($areExpectedEqual, $areEqual);
    }

    /**
     * @return Iterator<array{Type, Type, bool}>
     */
    public static function provideData(): Iterator
    {
        yield [new StringType(), new BooleanType(), false];
        yield [new StringType(), new StringType(), true];
        yield [new StringType(), new ClassStringType(), false];
        yield [new IntegerType(), new IntegerType(), true];
        yield [new IntegerType(), IntegerRangeType::fromInterval(1, 10), false];
    }
}
