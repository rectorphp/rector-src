<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockFromDataProviderRector\Fixture;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ComplexCase extends TestCase
{
    #[DataProvider('getData')]
    public function testSomething(array $first, array $second, int $expected): void
    {
    }

    public static function getData(): \Iterator
    {
        yield [
            [
                'key' => [
                    'anotherKey' => 123,
                ],
            ],
            [
                'expectedKey' => 456,
            ],
            456,
        ];
        yield [
            [],
            [
                'expectedKey' => 456,
            ],
            456,
        ];
        yield [
            [
                'key' => [
                    'anotherKey' => 123,
                ],
            ],
            [],
            123,
        ];
        yield [
            [],
            [],
            5,
        ];
    }

}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockFromDataProviderRector\Fixture;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ComplexCase extends TestCase
{
    /**
     * @param array<string, array<string, int>> $first
     * @param array<string, int> $second
     */
    #[DataProvider('getData')]
    public function testSomething(array $first, array $second, int $expected): void
    {
    }

    public static function getData(): \Iterator
    {
        yield [
            [
                'key' => [
                    'anotherKey' => 123,
                ],
            ],
            [
                'expectedKey' => 456,
            ],
            456,
        ];
        yield [
            [],
            [
                'expectedKey' => 456,
            ],
            456,
        ];
        yield [
            [
                'key' => [
                    'anotherKey' => 123,
                ],
            ],
            [],
            123,
        ];
        yield [
            [],
            [],
            5,
        ];
    }

}

?>
