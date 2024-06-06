<?php

declare(strict_types=1);

namespace Rector\BetterPhpDocParser\PhpDocInfo;

use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use Rector\BetterPhpDocParser\ValueObject\Parser\BetterTokenIterator;

final readonly class TokenIteratorFactory
{
    public function __construct(
        private Lexer $lexer
    ) {
    }

    public function create(string $content): BetterTokenIterator
    {
        $tokens = $this->lexer->tokenize($content);
        return new BetterTokenIterator($tokens);
    }

    public function createFromTokenIterator(TokenIterator $tokenIterator): BetterTokenIterator
    {
        if ($tokenIterator instanceof BetterTokenIterator) {
            return $tokenIterator;
        }

        // keep original tokens and index position
        $tokens = $tokenIterator->getTokens();
        $currentIndex = $tokenIterator->currentTokenIndex();

        return new BetterTokenIterator($tokens, $currentIndex);
    }
}
