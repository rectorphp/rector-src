<?php

declare(strict_types=1);

namespace Rector\Tests\Reporting\Source\SealedShape;

/**
 * @phpstan-sealed Circle|Square
 */
abstract class Shape
{
}

final class Circle extends Shape
{
}

final class Square extends Shape
{
}

final class ShapeChecker
{
    public function isRound(Shape $shape): bool
    {
        if ($shape instanceof Circle) {
            return true;
        }

        if ($shape instanceof Square) {
            return false;
        }

        throw new \LogicException('Unknown shape');
    }
}
