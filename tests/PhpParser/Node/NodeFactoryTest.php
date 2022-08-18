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
     * @return Iterator<mixed>
     */
    public function provideDataForArray(): Iterator
    {
        $lNumber = new LNumber(1);
        $string = new String_('a');
        $trueConstFetch = new ConstFetch(new Name('true'));
        $falseConstFetch = new ConstFetch(new Name('false'));
        $nullConstEtch = new ConstFetch(new Name('null'));

        $array = new Array_();
        $array->items[] = new ArrayItem($lNumber);
        yield [[1], $array];

        $array = new Array_();
        $array->items[] = new ArrayItem($lNumber, $string);
        yield [[
            'a' => 1,
        ], $array];

        $array = new Array_();
        $array->items[] = new ArrayItem($lNumber);
        yield [[$lNumber], $array];

        $array = new Array_();
        $array->items[] = new ArrayItem($string);
        yield [[$string], $array];

        $array = new Array_();
        $array->items[] = new ArrayItem($trueConstFetch);
        yield [[$trueConstFetch], $array];

        $array = new Array_();
        $array->items[] = new ArrayItem($falseConstFetch);
        yield [[$falseConstFetch], $array];

        $array = new Array_();
        $array->items[] = new ArrayItem($nullConstEtch);
        yield [[$nullConstEtch], $array];
    }
}
