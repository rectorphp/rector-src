<?php

declare(strict_types=1);

namespace Rector\Tests\PhpParser\Printer;

use PhpParser\PrettyPrinter\Standard;
use PHPStan\Parser\Parser;
use PHPStan\Parser\RichParser;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

/**
 * Test case for: https://github.com/rectorphp/rector/issues/9492
 * Most likely caused by https://github.com/phpstan/phpstan-src/pull/3763
 */
final class PHPStanPrinterTest extends AbstractLazyTestCase
{
    public function testAddingCommentOnSomeNodesFail(): void
    {
        /** @var RichParser $phpstanParser */
        $phpstanParser = $this->make(Parser::class);

        $stmts = $phpstanParser->parseFile(__DIR__ . '/Fixture/some_array_map.php');

        // get private property "parser"
        $parserReflectionProperty = new \ReflectionProperty(RichParser::class, 'parser');

        /** @var \PhpParser\Parser $innerParser */
        $innerParser = $parserReflectionProperty->getValue($phpstanParser);
        $tokens = $innerParser->getTokens();

        $standardPrinter = new Standard([
            'newline' => "\n",
        ]);
        $printerContents = $standardPrinter->printFormatPreserving($stmts, $stmts, $tokens);

        $this->assertStringEqualsFile(__DIR__ . '/Fixture/some_array_map.php', $printerContents);
    }
}
