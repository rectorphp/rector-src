<?php

declare(strict_types=1);

namespace Rector\Tests\PhpParser\Printer;

use PhpParser\PrettyPrinter\Standard;
use PHPStan\Parser\Parser;
use PHPStan\Parser\RichParser;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
<<<<<<< HEAD
use ReflectionProperty;
=======
>>>>>>> ea9a6ad8a5 ([issue 9492] create reproducer for modified array_map args, as creates changed args on print)

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
<<<<<<< HEAD
        $parserReflectionProperty = new ReflectionProperty(RichParser::class, 'parser');
=======
        $parserReflectionProperty = new \ReflectionProperty(RichParser::class, 'parser');
>>>>>>> ea9a6ad8a5 ([issue 9492] create reproducer for modified array_map args, as creates changed args on print)

        /** @var \PhpParser\Parser $innerParser */
        $innerParser = $parserReflectionProperty->getValue($phpstanParser);
        $tokens = $innerParser->getTokens();

<<<<<<< HEAD
        $standard = new Standard([]);
        $printerContents = $standard->printFormatPreserving($stmts, $stmts, $tokens);

        $newlineNormalizedContents = str_replace("\r\n", PHP_EOL, $printerContents);

        $this->assertStringEqualsFile(__DIR__ . '/Fixture/some_array_map.php', $newlineNormalizedContents);
=======
        $standardPrinter = new Standard([
            'newline' => "\n",
        ]);
        $printerContents = $standardPrinter->printFormatPreserving($stmts, $stmts, $tokens);

        $this->assertStringEqualsFile(__DIR__ . '/Fixture/some_array_map.php', $printerContents);
>>>>>>> ea9a6ad8a5 ([issue 9492] create reproducer for modified array_map args, as creates changed args on print)
    }
}
