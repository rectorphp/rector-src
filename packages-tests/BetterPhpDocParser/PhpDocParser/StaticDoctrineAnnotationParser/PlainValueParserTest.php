<?php

declare(strict_types=1);

namespace Rector\Tests\BetterPhpDocParser\PhpDocParser\StaticDoctrineAnnotationParser;

use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\ParserFactory;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\PhpDocInfo\TokenIteratorFactory;
use Rector\BetterPhpDocParser\PhpDocParser\ClassAnnotationMatcher;
use Rector\BetterPhpDocParser\PhpDocParser\StaticDoctrineAnnotationParser\PlainValueParser;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\Testing\TestingParser\TestingParser;

class PlainValueParserTest extends AbstractLazyTestCase
{
    public function testParseValue(): void
    {
        $tokenIteratorFactory = $this->make(TokenIteratorFactory::class);
        $testingParser = $this->make(TestingParser::class);
        $betterNodeFinder = $this->make(BetterNodeFinder::class);
        $phpDocInfoFactory = $this->make(PhpDocInfoFactory::class);

        $file = $testingParser->parseFilePathToFile(__DIR__ . '/Source/StringAnnotationValue.php');
        $parserFactory = new ParserFactory();
        $phpParser = $parserFactory->create(ParserFactory::PREFER_PHP7);
        $stmts = $phpParser->parse($file->getFileContent());
        if ($stmts === null) {
            $this->fail('No statements parsed');
        }

        $classMethodNode = $betterNodeFinder->findInstanceOf($stmts, ClassMethod::class);
        $phpDocInfo = $phpDocInfoFactory->createFromNode($classMethodNode[0]);

        if ($phpDocInfo === null) {
            $this->fail('$phpDocInfo is null');
        }

        /** @var PhpDocTagNode $phpDocTagNode */
        $phpDocTagNode = $phpDocInfo->getPhpDocNode()
            ->children[0];

        $annotationValue = $phpDocTagNode->value;
        $tokenIterator = $tokenIteratorFactory->create((string) $annotationValue);

        $plainValueParser = new PlainValueParser($this->make(ClassAnnotationMatcher::class));
        $plainValueParser->parseValue($tokenIterator, $classMethodNode[0]); // parse the key
        $tokenIterator->consumeTokenType(Lexer::TOKEN_EQUAL);
        $value = $plainValueParser->parseValue($tokenIterator, $classMethodNode[0]);

        $expected = 'List of value :
  - < b > TRY < /b>: To try
  - < b > TEST < /b>: to test ( Default if no parameters given )';

        $this->assertEquals($expected, $value);
    }
}
