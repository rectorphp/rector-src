<?php

declare(strict_types=1);

namespace Rector\PhpDocParser\PhpParser;

use PhpParser\NodeVisitor\NameResolver;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use PHPStan\Parser\CachedParser;
use PHPStan\Parser\SimpleParser;
use PHPStan\Parser\VariadicFunctionsVisitor;
use PHPStan\Parser\VariadicMethodsVisitor;

/**
 * Based on PHPStan-based PHP-Parser best practices:
 *
 * @see https://github.com/rectorphp/rector/issues/6744#issuecomment-950282826
 * @see https://github.com/phpstan/phpstan-src/blob/99e4ae0dced58fe0be7a7aec3168a5e9d639240a/conf/config.neon#L1669-L1691
 */
final class SmartPhpParserFactory
{
    /**
     * @api
     */
    public function create(): SmartPhpParser
    {
        $nativePhpParser = $this->createNativePhpParser();
        $cachedParser = $this->createPHPStanParser($nativePhpParser);

        return new SmartPhpParser($cachedParser);
    }

    private function createNativePhpParser(): Parser
    {
        $parserFactory = new ParserFactory();
        return $parserFactory->createForNewestSupportedVersion();
    }

    private function createPHPStanParser(Parser $parser): CachedParser
    {
        $nameResolver = new NameResolver();
        $variadicMethodsVisitor = new VariadicMethodsVisitor();
        $variadicFunctionsVisitor = new VariadicFunctionsVisitor();

        $simpleParser = new SimpleParser($parser, $nameResolver, $variadicMethodsVisitor, $variadicFunctionsVisitor);

        return new CachedParser($simpleParser, 1024);
    }
}
