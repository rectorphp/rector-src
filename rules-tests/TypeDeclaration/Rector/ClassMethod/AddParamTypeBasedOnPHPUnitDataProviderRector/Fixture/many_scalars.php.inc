<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeBasedOnPHPUnitDataProviderRector\Fixture;

use PHPUnit\Framework\TestCase;

/**
 * Test case including a dataProvider using an array that lead to an incorrect types being added.
 * `array` was added for $one, but should be `string`, or the parameter should be left alone entirely.
 * The $two and $three parameters did not get hints added at all.
 * See: https://phpunit.readthedocs.io/en/9.3/writing-tests-for-phpunit.html#data-providers
 */
final class ManyScalars extends TestCase
{
    public function provideThings(): array {
        return [
            [ 123, true, 'I am a string', null, 'a' ],
            [ 123, true, 'I am a string', 999, [] ],
            [ 123, true, 'I am a string', 999, 123 ],
        ];
    }

    /**
     * @dataProvider provideThings
     */
    public function testGetFromId( $one, $two, $three, $four, $five ): void {}
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeBasedOnPHPUnitDataProviderRector\Fixture;

use PHPUnit\Framework\TestCase;

/**
 * Test case including a dataProvider using an array that lead to an incorrect types being added.
 * `array` was added for $one, but should be `string`, or the parameter should be left alone entirely.
 * The $two and $three parameters did not get hints added at all.
 * See: https://phpunit.readthedocs.io/en/9.3/writing-tests-for-phpunit.html#data-providers
 */
final class ManyScalars extends TestCase
{
    public function provideThings(): array {
        return [
            [ 123, true, 'I am a string', null, 'a' ],
            [ 123, true, 'I am a string', 999, [] ],
            [ 123, true, 'I am a string', 999, 123 ],
        ];
    }

    /**
     * @dataProvider provideThings
     */
    public function testGetFromId( int $one, bool $two, string $three, ?int $four, $five ): void {}
}

?>
