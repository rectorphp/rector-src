<?php

declare(strict_types=1);

namespace Rector\Core\Tests\PhpParser\Printer;

use Iterator;
use PhpParser\Comment;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Expr\Yield_;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Core\PhpParser\Printer\BetterStandardPrinter;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class BetterStandardPrinterTest extends AbstractLazyTestCase
{
    private BetterStandardPrinter $betterStandardPrinter;

    protected function setUp(): void
    {
        $this->betterStandardPrinter = $this->make(BetterStandardPrinter::class);
    }

    public function testAddingCommentOnSomeNodesFail(): void
    {
        $methodCall = new MethodCall(new Variable('this'), 'run');

        // cannot be on MethodCall, must be Expression
        $methodCallExpression = new Expression($methodCall);
        $methodCallExpression->setAttribute(AttributeKey::COMMENTS, [new Comment('// todo: fix')]);

        $classMethod = new ClassMethod('run');
        $classMethod->stmts = [$methodCallExpression];

        $printed = $this->betterStandardPrinter->print($classMethod) . PHP_EOL;
        $printed = str_replace(PHP_EOL, "\n", $printed);

        $this->assertStringEqualsFile(
            __DIR__ . '/Source/expected_code_with_non_stmt_placed_nested_comment.php.inc',
            $printed
        );
    }

    public function testStringWithAddedComment(): void
    {
        $string = new String_('hey');
        $string->setAttribute(AttributeKey::COMMENTS, [new Comment('// todo: fix')]);

        $printed = $this->betterStandardPrinter->print($string) . PHP_EOL;
        $printed = str_replace(PHP_EOL, "\n", $printed);

        $this->assertStringEqualsFile(__DIR__ . '/Source/expected_code_with_comment.php.inc', $printed);
    }

    #[DataProvider('provideDataForDoubleSlashEscaping')]
    public function testDoubleSlashEscaping(string $content, string $expectedOutput): void
    {
        $printed = $this->betterStandardPrinter->print(new String_($content));
        $this->assertSame($expectedOutput, $printed);
    }

    /**
     * @return Iterator<string[]>
     */
    public static function provideDataForDoubleSlashEscaping(): Iterator
    {
        yield ['Vendor\Name', "'Vendor\Name'"];
        yield ['Vendor\\', "'Vendor\\\\'"];
        yield ["Vendor'Name", "'Vendor\'Name'"];
    }

    #[DataProvider('provideDataForYield')]
    public function testYield(Node $node, string $expectedPrintedNode): void
    {
        $printedNode = $this->betterStandardPrinter->print($node);
        $this->assertSame($expectedPrintedNode, $printedNode);
    }

    public static function provideDataForYield(): Iterator
    {
        $yield = new Yield_(new String_('value'));
        yield [$yield, "yield 'value'"];

        yield [new Yield_(), 'yield'];

        $expression = new Expression($yield);
        yield [$expression, "yield 'value';"];

        $assignedToYield = clone $yield;
        $assignedToYield->setAttribute(AttributeKey::IS_ASSIGNED_TO, true);
        yield [$assignedToYield, "(yield 'value')"];
    }
}
