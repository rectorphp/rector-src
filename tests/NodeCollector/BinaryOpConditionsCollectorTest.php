<?php

declare(strict_types=1);

namespace Rector\Tests\NodeCollector;

use PhpParser\Node\Expr\BinaryOp\Minus;
use PhpParser\Node\Expr\BinaryOp\Plus;
use PhpParser\Node\Expr\Variable;
use PHPUnit\Framework\TestCase;
use Rector\NodeCollector\BinaryOpConditionsCollector;

final class BinaryOpConditionsCollectorTest extends TestCase
{
    public function testLeftAssociative(): void
    {
        $binaryOpConditionsCollector = new BinaryOpConditionsCollector();

        // (Plus (Plus a b) c)
        $firstVariable = new Variable('a');
        $secondVariable = new Variable('b');
        $thirdVariable = new Variable('c');

        $abcPlus = new Plus(new Plus($firstVariable, $secondVariable), $thirdVariable);

        $result = $binaryOpConditionsCollector->findConditions($abcPlus, Plus::class);

        $this->assertSame([
            2 => $firstVariable,
            1 => $secondVariable,
            0 => $thirdVariable,
        ], $result);
    }

    public function testRightAssociative(): void
    {
        $binaryOpConditionsCollector = new BinaryOpConditionsCollector();

        // (Plus a (Plus b c))
        $firstVariable = new Variable('a');
        $secondVariable = new Variable('b');
        $thirdVariable = new Variable('c');

        $bcPlus = new Plus($secondVariable, $thirdVariable);
        $abcPlus = new Plus($firstVariable, $bcPlus);

        $result = $binaryOpConditionsCollector->findConditions($abcPlus, Plus::class);

        $this->assertSame([
            1 => $firstVariable,
            0 => $bcPlus,
        ], $result);
    }

    public function testWrongRootOp(): void
    {
        $binaryOpConditionsCollector = new BinaryOpConditionsCollector();

        // (Minus (Plus a b) c)
        $firstVariable = new Variable('a');
        $secondVariable = new Variable('b');
        $thirdVariable = new Variable('c');

        $abcMinus = new Minus(new Plus($firstVariable, $secondVariable), $thirdVariable);

        $result = $binaryOpConditionsCollector->findConditions($abcMinus, Plus::class);

        $this->assertSame([
            0 => $abcMinus,
        ], $result);
    }

    public function testTrivialCase(): void
    {
        $binaryOpConditionsCollector = new BinaryOpConditionsCollector();

        $variable = new Variable('a');

        $result = $binaryOpConditionsCollector->findConditions($variable, Plus::class);

        $this->assertSame([
            0 => $variable,
        ], $result);
    }

    public function testInnerNodeDifferentOp(): void
    {
        $binaryOpConditionsCollector = new BinaryOpConditionsCollector();

        // (Plus (Minus a b) c)
        $firstVariable = new Variable('a');
        $secondVariable = new Variable('b');
        $thirdVariable = new Variable('c');

        $abMinus = new Minus($firstVariable, $secondVariable);
        $abcPlus = new Plus($abMinus, $thirdVariable);

        $result = $binaryOpConditionsCollector->findConditions($abcPlus, Plus::class);

        $this->assertSame([
            1 => $abMinus,
            0 => $thirdVariable,
        ], $result);
    }
}
