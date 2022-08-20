<?php

declare(strict_types=1);

namespace Rector\Tests\BetterPhpDocParser\PhpDocParser\StaticDoctrineAnnotationParser;

use Iterator;
use PhpParser\Node\Scalar\String_;
use Rector\BetterPhpDocParser\PhpDoc\ArrayItemNode;
use Rector\BetterPhpDocParser\PhpDocInfo\TokenIteratorFactory;
use Rector\BetterPhpDocParser\PhpDocParser\StaticDoctrineAnnotationParser\ArrayParser;
use Rector\Testing\PHPUnit\AbstractTestCase;

final class ArrayParserTest extends AbstractTestCase
{
    private ArrayParser $arrayParser;

    private TokenIteratorFactory $tokenIteratorFactory;

    protected function setUp(): void
    {
        $this->boot();

        $this->arrayParser = $this->getService(ArrayParser::class);
        $this->tokenIteratorFactory = $this->getService(TokenIteratorFactory::class);
    }

    /**
     * @dataProvider provideData()
     *
     * @param ArrayItemNode[] $expectedArrayItemNodes
     */
    public function test(string $docContent, array $expectedArrayItemNodes): void
    {
        $betterTokenIterator = $this->tokenIteratorFactory->create($docContent);

        $arrayItemNodes = $this->arrayParser->parseCurlyArray($betterTokenIterator);
        $this->assertEquals($expectedArrayItemNodes, $arrayItemNodes);
    }

    public function provideData(): Iterator
    {
        yield ['{key: "value"}', [new ArrayItemNode('value', 'key', String_::KIND_DOUBLE_QUOTED)]];

        yield ['{"key": "value"}', [
            new ArrayItemNode('value', 'key', String_::KIND_DOUBLE_QUOTED, String_::KIND_DOUBLE_QUOTED),
        ]];

        yield ['{"value", "value2"}', [
            new ArrayItemNode('value', null, String_::KIND_DOUBLE_QUOTED),
            new ArrayItemNode('value2', null, String_::KIND_DOUBLE_QUOTED),
        ]];
    }
}
