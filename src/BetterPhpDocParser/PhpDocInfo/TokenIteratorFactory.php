<?php

declare(strict_types=1);

namespace Rector\BetterPhpDocParser\PhpDocInfo;

use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\TokenIterator;

final readonly class TokenIteratorFactory
{
    public function __construct(
        private Lexer $lexer
    ) {
    }

    public function create(string $content): TokenIterator
    {
        $tokens = $this->lexer->tokenize($content);
        return new TokenIterator($tokens);
    }
}
