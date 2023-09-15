<?php

declare(strict_types=1);

namespace Rector\Tests\BetterPhpDocParser\PhpDocParser\StaticDoctrineAnnotationParser;

use Iterator;
use PhpParser\Node\Scalar\String_;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\BetterPhpDocParser\PhpDoc\ArrayItemNode;
use Rector\BetterPhpDocParser\PhpDoc\StringNode;
use Rector\BetterPhpDocParser\PhpDocInfo\TokenIteratorFactory;
use Rector\BetterPhpDocParser\PhpDocParser\StaticDoctrineAnnotationParser;
use Rector\BetterPhpDocParser\ValueObject\PhpDoc\DoctrineAnnotation\CurlyListNode;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class StaticDoctrineAnnotationParserTest extends AbstractLazyTestCase
{
    private StaticDoctrineAnnotationParser $staticDoctrineAnnotationParser;

    private TokenIteratorFactory $tokenIteratorFactory;

    protected function setUp(): void
    {
        $this->tokenIteratorFactory = $this->make(TokenIteratorFactory::class);
        $this->staticDoctrineAnnotationParser = $this->make(StaticDoctrineAnnotationParser::class);
    }

    /**
     * @param CurlyListNode|array<string, CurlyListNode> $expectedValue
     */
    #[DataProvider('provideData')]
    public function test(string $docContent, CurlyListNode | array $expectedValue): void
    {
        $betterTokenIterator = $this->tokenIteratorFactory->create($docContent);

        $string = new String_('some_node');
        $value = $this->staticDoctrineAnnotationParser->resolveAnnotationValue($betterTokenIterator, $string);

        // "equals" on purpose to compare 2 object with same content
        $this->assertEquals($expectedValue, $value);
    }

    public static function provideData(): Iterator
    {
        $curlyListNode = new CurlyListNode([
            new ArrayItemNode(new StringNode('chalet')),
            new ArrayItemNode(new StringNode('apartment')),
        ]);
        yield ['{"chalet", "apartment"}', $curlyListNode];

        yield [
            'key={"chalet", "apartment"}', [
                'key' => $curlyListNode,
            ],
        ];
    }
}
