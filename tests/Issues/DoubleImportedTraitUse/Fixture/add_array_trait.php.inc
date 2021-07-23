<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\DoubleImportedTraitUse\Fixture;

use Generator;
use Iterator;
use PHPStan\Testing\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

final class SomeTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @dataProvider invalidValues()
     */
    public function testInvalidValues(stdClass | array $value): void
    {
    }

    /**
     * @return Generator<mixed, mixed>
     */
    public function invalidValues(): Iterator
    {
        yield 'Class without __toString not possible' => [new stdClass()];
        yield 'Array is not possible' => [[]];
    }
}
