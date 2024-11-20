<?php

declare(strict_types=1);

namespace Rector\Tests\PhpParser\Node;

use PhpParser\Node\Scalar\Int_;
use Iterator;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\PhpParser\Node\NodeFactory;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class NodeFactoryTest extends AbstractLazyTestCase
{
    private NodeFactory $nodeFactory;

    protected function setUp(): void
    {
        $this->nodeFactory = $this->make(NodeFactory::class);
    }

    /**
     * @param int[]|array<string, int> $inputArray
     */
    #[DataProvider('provideDataForArray')]
    public function testCreateArray(array $inputArray, Array_ $expectedArray): void
    {
        $arrayNode = $this->nodeFactory->createArray($inputArray);

        $this->assertEquals($expectedArray, $arrayNode);
    }

    /**
     * @return Iterator<mixed>
     */
    public static function provideDataForArray(): Iterator
    {
        $int = new Int_(1);
        $string = new String_('a');
        $trueConstFetch = new ConstFetch(new Name('true'));
        $falseConstFetch = new ConstFetch(new Name('false'));
        $nullConstEtch = new ConstFetch(new Name('null'));

        $array = new Array_();
        $array->items[] = new ArrayItem($int);
        yield [[1], $array];

        $array = new Array_();
        $array->items[] = new ArrayItem($int, $string);
        yield [[
            'a' => 1,
        ], $array];

        $array = new Array_();
        $array->items[] = new ArrayItem($int);
        yield [[$int], $array];

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
