<?php

declare(strict_types=1);

namespace Rector\PhpDocParser\PhpDocParser\StaticFactory;

use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TypeParser;
use Rector\PhpDocParser\PhpDocParser\SimplePhpDocParser;

/**
 * @api
 */
final class SimplePhpDocParserStaticFactory
{
    public static function create(): SimplePhpDocParser
    {
        $phpDocParser = new PhpDocParser(new TypeParser(), new ConstExprParser());
        return new SimplePhpDocParser($phpDocParser, new Lexer());
    }
}
