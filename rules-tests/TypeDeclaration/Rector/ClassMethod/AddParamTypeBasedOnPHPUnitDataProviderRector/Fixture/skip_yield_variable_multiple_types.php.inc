<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeBasedOnPHPUnitDataProviderRector\Fixture;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class SkipYieldVariableMultipleTypes extends TestCase
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
