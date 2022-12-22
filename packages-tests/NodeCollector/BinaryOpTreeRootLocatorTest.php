<?php

declare(strict_types=1);

namespace Rector\Tests\NodeCollector;

use PhpParser\Node\Expr\BinaryOp\Minus;
use PhpParser\Node\Expr\BinaryOp\Plus;
use PhpParser\Node\Expr\Variable;
use PHPUnit\Framework\TestCase;
use Rector\NodeCollector\BinaryOpTreeRootLocator;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class BinaryOpTreeRootLocatorTest extends TestCase
{
    public function testLeftAssociative(): void
    {
        $binaryOpTreeRootLocator = new BinaryOpTreeRootLocator();

        // (Plus (Plus a b) c)
        $firstVariable = new Variable('a');
        $secondVariable = new Variable('b');
        $abPlus = new Plus($firstVariable, $secondVariable);
        $firstVariable->setAttribute(AttributeKey::PARENT_NODE, $abPlus);
        $secondVariable->setAttribute(AttributeKey::PARENT_NODE, $abPlus);

        $thirdVariable = new Variable('c');
        $abcPlus = new Plus($abPlus, $thirdVariable);
        $abPlus->setAttribute(AttributeKey::PARENT_NODE, $abcPlus);
        $thirdVariable->setAttribute(AttributeKey::PARENT_NODE, $abcPlus);

        $firstOperationRoot = $binaryOpTreeRootLocator->findOperationRoot($firstVariable, Plus::class);
        $this->assertSame($abcPlus, $firstOperationRoot);

        $secondOperationRoot = $binaryOpTreeRootLocator->findOperationRoot($secondVariable, Plus::class);
        $this->assertSame($abcPlus, $secondOperationRoot);

        $plusOperationRoot = $binaryOpTreeRootLocator->findOperationRoot($abPlus, Plus::class);
        $this->assertSame($abcPlus, $plusOperationRoot);

        $thirdOperationRoot = $binaryOpTreeRootLocator->findOperationRoot($thirdVariable, Plus::class);
        $this->assertSame($abcPlus, $thirdOperationRoot);
    }

    public function testRightAssociative(): void
    {
        $binaryOpTreeRootLocator = new BinaryOpTreeRootLocator();

        // (Plus a (Plus b c))
        $firstVariable = new Variable('a');
        $secondVariable = new Variable('b');
        $thirdVariable = new Variable('c');

        $bcPlus = new Plus($secondVariable, $thirdVariable);
        $secondVariable->setAttribute(AttributeKey::PARENT_NODE, $bcPlus);
        $thirdVariable->setAttribute(AttributeKey::PARENT_NODE, $bcPlus);

        $abcPlus = new Plus($firstVariable, $bcPlus);
        $firstVariable->setAttribute(AttributeKey::PARENT_NODE, $abcPlus);
        $bcPlus->setAttribute(AttributeKey::PARENT_NODE, $abcPlus);

        $firstOperationRoot = $binaryOpTreeRootLocator->findOperationRoot($firstVariable, Plus::class);
        $this->assertSame($abcPlus, $firstOperationRoot);

        $secondOperationRoot = $binaryOpTreeRootLocator->findOperationRoot($secondVariable, Plus::class);
        $this->assertSame($bcPlus, $secondOperationRoot);

        $thirdOperationRoot = $binaryOpTreeRootLocator->findOperationRoot($thirdVariable, Plus::class);
        $this->assertSame($bcPlus, $thirdOperationRoot);

        $plusOperationRoot = $binaryOpTreeRootLocator->findOperationRoot($bcPlus, Plus::class);
        $this->assertSame($bcPlus, $plusOperationRoot);
    }

    public function testWrongRootOp(): void
    {
        $binaryOpTreeRootLocator = new BinaryOpTreeRootLocator();

        // (Minus (Plus a b) c)
        $firstVariable = new Variable('a');
        $secondVariable = new Variable('b');

        $abPlus = new Plus($firstVariable, $secondVariable);
        $firstVariable->setAttribute(AttributeKey::PARENT_NODE, $abPlus);
        $secondVariable->setAttribute(AttributeKey::PARENT_NODE, $abPlus);

        $thirdVariable = new Variable('c');
        $abcMinus = new Minus($abPlus, $thirdVariable);
        $abPlus->setAttribute(AttributeKey::PARENT_NODE, $abcMinus);
        $thirdVariable->setAttribute(AttributeKey::PARENT_NODE, $abcMinus);

        $plusOperationRoot = $binaryOpTreeRootLocator->findOperationRoot($firstVariable, Plus::class);
        $this->assertSame($abPlus, $plusOperationRoot);

        $secondOperationRoot = $binaryOpTreeRootLocator->findOperationRoot($secondVariable, Plus::class);
        $this->assertSame($abPlus, $secondOperationRoot);

        $plusOperationRoot = $binaryOpTreeRootLocator->findOperationRoot($abPlus, Plus::class);
        $this->assertSame($abPlus, $plusOperationRoot);

        $thirdOperationRoot = $binaryOpTreeRootLocator->findOperationRoot($thirdVariable, Plus::class);
        $this->assertSame($thirdVariable, $thirdOperationRoot);
    }

    public function testInnerNodeDifferentOp(): void
    {
        $binaryOpTreeRootLocator = new BinaryOpTreeRootLocator();

        // (Plus (Minus a b) c)
        $firstVariable = new Variable('a');
        $secondVariable = new Variable('b');
        $thirdVariable = new Variable('c');

        $abMinus = new Minus($firstVariable, $secondVariable);
        $firstVariable->setAttribute(AttributeKey::PARENT_NODE, $abMinus);
        $secondVariable->setAttribute(AttributeKey::PARENT_NODE, $abMinus);

        $abcPlus = new Plus($abMinus, $thirdVariable);
        $abMinus->setAttribute(AttributeKey::PARENT_NODE, $abcPlus);
        $thirdVariable->setAttribute(AttributeKey::PARENT_NODE, $abcPlus);

        $firstOperationRoot = $binaryOpTreeRootLocator->findOperationRoot($firstVariable, Plus::class);
        $this->assertSame($firstVariable, $firstOperationRoot);

        $secondOperationRoot = $binaryOpTreeRootLocator->findOperationRoot($secondVariable, Plus::class);
        $this->assertSame($secondVariable, $secondOperationRoot);

        $minusOperationRoot = $binaryOpTreeRootLocator->findOperationRoot($abMinus, Plus::class);
        $this->assertSame($abcPlus, $minusOperationRoot);

        $thirdOperationRoot = $binaryOpTreeRootLocator->findOperationRoot($thirdVariable, Plus::class);
        $this->assertSame($abcPlus, $thirdOperationRoot);
    }
}
