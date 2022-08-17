<?php

declare(strict_types=1);

namespace Rector\Core\Tests\PhpParser\Node;

use Iterator;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use Rector\Core\PhpParser\Node\NodeFactory;
use Rector\Testing\PHPUnit\AbstractTestCase;

final class NodeFactoryTest extends AbstractTestCase
{
    private NodeFactory $nodeFactory;

    protected function setUp(): void
    {
        $this->boot();

        $this->nodeFactory = $this->getService(NodeFactory::class);
    }

    /**
     * @param int[]|array<string, int> $inputArray
     * @dataProvider provideDataForArray()
     */
    public function testCreateArray(array $inputArray, Array_ $expectedArrayNode): void
    {
        $arrayNode = $this->nodeFactory->createArray($inputArray);

        $this->assertEquals($expectedArrayNode, $arrayNode);
    }

    /**
     * @return Iterator<int[][]|array<string, int>|Array_[]>
     */
    public function provideDataForArray(): Iterator
    {
        $numberNode = new LNumber(1);
        $stringNode = new String_('a');
        $trueNode = new ConstFetch(new Name('true'));
        $falseNode = new ConstFetch(new Name('false'));
        $nullNode = new ConstFetch(new Name('null'));

        $array = new Array_();
        $array->items[] = new ArrayItem($numberNode);

        yield [[1], $array];

        $array = new Array_();
        $array->items[] = new ArrayItem($numberNode, $stringNode);

        yield [[
            'a' => 1,
        ], $array];

        $array = new Array_();
        $array->items[] = new ArrayItem($numberNode);

        yield [[$numberNode], $array];

        $array = new Array_();
        $array->items[] = new ArrayItem($stringNode);

        yield [[$stringNode], $array];

        $array = new Array_();
        $array->items[] = new ArrayItem($trueNode);

        yield [[$trueNode], $array];

        $array = new Array_();
        $array->items[] = new ArrayItem($falseNode);

        yield [[$falseNode], $array];

        $array = new Array_();
        $array->items[] = new ArrayItem($nullNode);

        yield [[$nullNode], $array];
    }
}
