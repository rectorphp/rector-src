<?php

declare(strict_types=1);

namespace Rector\Tests\PhpAttribute\AnnotationToAttributeMapper;

use Iterator;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\PhpAttribute\AnnotationToAttributeMapper;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class AnnotationToAttributeMapperTest extends AbstractLazyTestCase
{
    private AnnotationToAttributeMapper $annotationToAttributeMapper;

    protected function setUp(): void
    {
        $this->annotationToAttributeMapper = $this->make(AnnotationToAttributeMapper::class);
    }

    /**
     * @param class-string<Expr> $expectedTypeClass
     */
    #[DataProvider('provideData')]
    public function test(mixed $input, string $expectedTypeClass): void
    {
        $mappedExpr = $this->annotationToAttributeMapper->map($input);
        $this->assertInstanceOf($expectedTypeClass, $mappedExpr);

        if ($mappedExpr instanceof Array_) {
            $arrayItem = $mappedExpr->items[0];
            $this->assertInstanceOf(ArrayItem::class, $arrayItem);
            $this->assertInstanceOf(String_::class, $arrayItem->value);
        }
    }

    public static function provideData(): Iterator
    {
        yield [false, ConstFetch::class];
        yield ['false', ConstFetch::class];
        yield ['100', LNumber::class];
        yield ['hey', String_::class];
        yield [['hey'], Array_::class];
    }
}
