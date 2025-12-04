<?php

declare(strict_types=1);

namespace Rector\Tests\PhpParser\Printer;

use PhpParser\PrettyPrinter\Standard;
use PHPStan\Parser\Parser;
use PHPStan\Parser\RichParser;
use Rector\DependencyInjection\PHPStan\PHPStanContainerMemento;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use ReflectionProperty;

/**
 * Test case for: https://github.com/rectorphp/rector/issues/9492
 * Most likely caused by https://github.com/phpstan/phpstan-src/pull/3763
 *
 * @see https://github.com/phpstan/phpstan-src/blob/2.1.x/src/Parser/ArrayMapArgVisitor.php
 */
final class PHPStanPrinterTest extends AbstractLazyTestCase
{
    public function testAddingCommentOnSomeNodesFail(): void
    {
        /** @var RichParser $phpstanParser */
        $phpstanParser = $this->make(Parser::class);

        PHPStanContainerMemento::removeRichVisitors($phpstanParser);

        $stmts = $phpstanParser->parseFile(__DIR__ . '/Fixture/some_array_map.php');

        // get private property "parser"
        $parserReflectionProperty = new ReflectionProperty(RichParser::class, 'parser');

        /** @var \PhpParser\Parser $innerParser */
        $innerParser = $parserReflectionProperty->getValue($phpstanParser);
        $tokens = $innerParser->getTokens();

        $standard = new Standard([]);
        $printerContents = $standard->printFormatPreserving($stmts, $stmts, $tokens);

        $newlineNormalizedContents = str_replace("\r\n", PHP_EOL, $printerContents);

        $this->assertStringEqualsFile(__DIR__ . '/Fixture/some_array_map.php', $newlineNormalizedContents);
    }
}
