<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRector;

use Iterator;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\Empty_;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class SimplifyEmptyCheckOnEmptyArrayRectorTest extends AbstractRectorTestCase
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

    #[DataProvider('provideUnsupportedNodes')]
    public function testSkipsUnsupportedNodesWithoutScope(BooleanNot|Empty_ $expr): void
    {
        $rector = self::getContainer()->make(SimplifyEmptyCheckOnEmptyArrayRector::class);

        $this->assertNull($rector->refactor($expr));
    }

    /**
     * @return Iterator<string, array{BooleanNot|Empty_}>
     */
    public static function provideUnsupportedNodes(): Iterator
    {
        yield 'plain negation' => [new BooleanNot(new Variable('values'))];
        yield 'empty array literal' => [new Empty_(new Array_())];
        yield 'negated empty function call' => [new BooleanNot(new Empty_(new FuncCall(new Name('getValues'))))];
        yield 'superglobal get' => [new Empty_(new Variable('_GET'))];
        yield 'negated superglobal post' => [new BooleanNot(new Empty_(new Variable('_POST')))];
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
