<?php

declare(strict_types=1);

namespace Rector\PhpDocParser\PhpDocParser;

use PhpParser\Comment\Doc;
use PhpParser\Node;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use Rector\PhpDocParser\PhpDocParser\ValueObject\Ast\PhpDoc\SimplePhpDocNode;

/**
 * @api
 * @see \Rector\Tests\PhpDocParser\PhpDocParser\SimplePhpDocParser\SimplePhpDocParserTest
 */
final class SimplePhpDocParser
{
    public function __construct(
        private readonly PhpDocParser $phpDocParser,
        private readonly Lexer $lexer
    ) {
    }

    public function parseNode(Node $node): ?SimplePhpDocNode
    {
        $docComment = $node->getDocComment();
        if (! $docComment instanceof Doc) {
            return null;
        }

        return $this->parseDocBlock($docComment->getText());
    }

    /**
     * @api
     */
    public function parseDocBlock(string $docBlock): SimplePhpDocNode
    {
        $tokens = $this->lexer->tokenize($docBlock);
        $tokenIterator = new TokenIterator($tokens);

        $phpDocNode = $this->phpDocParser->parse($tokenIterator);
        return new SimplePhpDocNode($phpDocNode->children);
    }
}
