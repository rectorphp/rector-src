<?php

declare(strict_types=1);

namespace Rector\BetterPhpDocParser\PhpDocParser\StaticDoctrineAnnotationParser;

use PhpParser\Node\Scalar\String_;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprIntegerNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use Rector\BetterPhpDocParser\PhpDoc\ArrayItemNode;
use Rector\BetterPhpDocParser\ValueObject\Parser\BetterTokenIterator;

/**
 * @see \Rector\Tests\BetterPhpDocParser\PhpDocParser\StaticDoctrineAnnotationParser\ArrayParserTest
 */
final class ArrayParser
{
    public function __construct(
        private readonly PlainValueParser $plainValueParser
    ) {
    }

    /**
     * Mimics https://github.com/doctrine/annotations/blob/c66f06b7c83e9a2a7523351a9d5a4b55f885e574/lib/Doctrine/Common/Annotations/DocParser.php#L1305-L1352
     *
     * @return ArrayItemNode[]
     */
    public function parseCurlyArray(BetterTokenIterator $tokenIterator): array
    {
        $values = [];

        // nothing
        if ($tokenIterator->isCurrentTokenType(Lexer::TOKEN_CLOSE_CURLY_BRACKET)) {
            return [];
        }

        $tokenIterator->consumeTokenType(Lexer::TOKEN_OPEN_CURLY_BRACKET);

        // If the array is empty, stop parsing and return.
        if ($tokenIterator->isCurrentTokenType(Lexer::TOKEN_CLOSE_CURLY_BRACKET)) {
            $tokenIterator->consumeTokenType(Lexer::TOKEN_CLOSE_CURLY_BRACKET);
            return [];
        }

        // first item
        $values[] = $this->resolveArrayItem($tokenIterator);

        // 2nd+ item
        while ($tokenIterator->isCurrentTokenType(Lexer::TOKEN_COMMA)) {
            // optional trailing comma
            $tokenIterator->consumeTokenType(Lexer::TOKEN_COMMA);
            $tokenIterator->tryConsumeTokenType(Lexer::TOKEN_PHPDOC_EOL);

            if ($tokenIterator->isCurrentTokenType(Lexer::TOKEN_CLOSE_CURLY_BRACKET)) {
                break;
            }

            $values[] = $this->resolveArrayItem($tokenIterator);
            if ($tokenIterator->isNextTokenType(Lexer::TOKEN_CLOSE_CURLY_BRACKET)) {
                break;
            }

            // skip newlines
            $tokenIterator->tryConsumeTokenType(Lexer::TOKEN_PHPDOC_EOL);
        }

        $tokenIterator->tryConsumeTokenType(Lexer::TOKEN_PHPDOC_EOL);

        // special case for nested doctrine annotations
        if (! $tokenIterator->isCurrentTokenType(Lexer::TOKEN_CLOSE_PARENTHESES)) {
            $tokenIterator->tryConsumeTokenType(Lexer::TOKEN_CLOSE_CURLY_BRACKET);
        }

        return $this->createArrayFromValues($values);
    }

    /**
     * Mimics https://github.com/doctrine/annotations/blob/c66f06b7c83e9a2a7523351a9d5a4b55f885e574/lib/Doctrine/Common/Annotations/DocParser.php#L1354-L1385
     * @return array<null|mixed, mixed>
     */
    private function resolveArrayItem(BetterTokenIterator $tokenIterator): array
    {
        // skip newlines
        $tokenIterator->tryConsumeTokenType(Lexer::TOKEN_PHPDOC_EOL);

        $key = null;

        // join "ClassName::CONSTANT_REFERENCE" to identifier
        if ($tokenIterator->isNextTokenTypes([Lexer::TOKEN_DOUBLE_COLON])) {
            $key = $tokenIterator->currentTokenValue();

            // "::"
            $tokenIterator->next();
            $key .= $tokenIterator->currentTokenValue();

            $tokenIterator->consumeTokenType(Lexer::TOKEN_DOUBLE_COLON);
            $key .= $tokenIterator->currentTokenValue();

            $tokenIterator->next();
        }

        $tokenIterator->tryConsumeTokenType(Lexer::TOKEN_PHPDOC_EOL);

        if ($tokenIterator->isCurrentTokenType(Lexer::TOKEN_CLOSE_CURLY_BRACKET, Lexer::TOKEN_COMMA)) {
            // it's a value, not a key
            return [null, $key];
        }

        if (
            $tokenIterator->isCurrentTokenType(Lexer::TOKEN_EQUAL, Lexer::TOKEN_COLON) ||
            $tokenIterator->isNextTokenTypes([Lexer::TOKEN_EQUAL, Lexer::TOKEN_COLON])
        ) {
            $tokenIterator->tryConsumeTokenType(Lexer::TOKEN_EQUAL);
            $tokenIterator->tryConsumeTokenType(Lexer::TOKEN_COLON);

            if ($key === null) {
                if ($tokenIterator->isNextTokenType(Lexer::TOKEN_IDENTIFIER)) {
                    $key = $this->plainValueParser->parseValue($tokenIterator);
                } else {
                    $tokenIterator->tryConsumeTokenType(Lexer::TOKEN_COMMA);
                    $key = $this->plainValueParser->parseValue($tokenIterator);
                }
            }

            $tokenIterator->tryConsumeTokenType(Lexer::TOKEN_EQUAL);
            $tokenIterator->tryConsumeTokenType(Lexer::TOKEN_COLON);

            return [$key, $this->plainValueParser->parseValue($tokenIterator)];
        }

        return [$key, $this->plainValueParser->parseValue($tokenIterator)];
    }

    /**
     * @param mixed[] $values
     * @return ArrayItemNode[]
     */
    private function createArrayFromValues(array $values): array
    {
        $arrayItemNodes = [];

        foreach ($values as $value) {
            [$key, $val] = $value;

            if ($key instanceof ConstExprIntegerNode) {
                $key = $key->value;
            }

            $valueQuoteKind = $this->resolveQuoteKind($val);
            if (is_string($val) && $valueQuoteKind === String_::KIND_DOUBLE_QUOTED) {
                // give raw value
                $val = trim($val, '"');
            }

            $keyQuoteKind = $this->resolveQuoteKind($key);
            if (is_string($key) && $keyQuoteKind === String_::KIND_DOUBLE_QUOTED) {
                // give raw value
                $key = trim($key, '"');
            }

            if ($key !== null) {
                $arrayItemNodes[] = new ArrayItemNode($val, $key, $valueQuoteKind, $keyQuoteKind);
            } else {
                $arrayItemNodes[] = new ArrayItemNode($val, null, $valueQuoteKind, $keyQuoteKind);
            }
        }

        return $arrayItemNodes;
    }

    /**
     * @return String_::KIND_SINGLE_QUOTED|String_::KIND_DOUBLE_QUOTED|null
     */
    private function resolveQuoteKind(mixed $val): ?int
    {
        if (is_string($val) && str_starts_with($val, '"') && str_ends_with($val, '"')) {
            return String_::KIND_DOUBLE_QUOTED;
        }

        if (is_string($val) && str_starts_with($val, "'") && str_ends_with($val, "'")) {
            return String_::KIND_SINGLE_QUOTED;
        }

        return null;
    }
}
