<?php

declare(strict_types=1);

namespace Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector;

use Iterator;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Php81\Rector\Array_\ArrayToFirstClassCallableRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class ArrayToFirstClassCallableRectorTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    #[DataProvider('provideSkippedArrays')]
    public function testSkipWithoutScope(Array_ $array): void
    {
        $rector = $this->make(ArrayToFirstClassCallableRector::class);

        $this->assertNull($rector->refactor($array));
    }

    /**
     * @return Iterator<string, array{Array_}>
     */
    public static function provideSkippedArrays(): Iterator
    {
        $caller = new ArrayItem(new Variable('this'));
        $method = new ArrayItem(new String_('run'));

        yield 'empty array' => [new Array_()];
        yield 'one item' => [new Array_([$caller])];
        yield 'three items' => [new Array_([$caller, $method, $method])];

        foreach ([
            AttributeKey::IS_CLASS_CONST_VALUE,
            AttributeKey::IS_DEFAULT_PROPERTY_VALUE,
            AttributeKey::IS_PARAM_DEFAULT,
            AttributeKey::IS_UNPACKED_ARG_VALUE,
        ] as $attribute) {
            yield $attribute => [
                new Array_([$caller, $method], [
                    $attribute => true,
                ])];
        }
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
