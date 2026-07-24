<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeBasedOnPHPUnitDataProviderRector\FixtureUnion;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class YieldVariableMultipleTypes extends TestCase
{
    /**
     * @dataProvider provideData
     */
    public function test(bool $expected, $datumEzl): void
    {
    }

    public static function provideData(): iterable
    {
        $erstZulassung = new DateTimeImmutable('-8 months');

        $cases = [
            [true, $erstZulassung],
            [false, $erstZulassung],
        ];

        foreach ($cases as $case) {
            yield $case;
            yield [$case[0], $case[1]->format('Y-m-d')];
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeBasedOnPHPUnitDataProviderRector\FixtureUnion;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class YieldVariableMultipleTypes extends TestCase
{
    /**
     * @dataProvider provideData
     */
    public function test(bool $expected, \DateTimeImmutable|string $datumEzl): void
    {
    }

    public static function provideData(): iterable
    {
        $erstZulassung = new DateTimeImmutable('-8 months');

        $cases = [
            [true, $erstZulassung],
            [false, $erstZulassung],
        ];

        foreach ($cases as $case) {
            yield $case;
            yield [$case[0], $case[1]->format('Y-m-d')];
        }
    }
}

?>
