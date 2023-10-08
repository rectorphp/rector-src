<?php

declare(strict_types=1);

namespace Rector\BetterPhpDocParser\PhpDocParser\StaticDoctrineAnnotationParser;

use PhpParser\Node;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprFalseNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprIntegerNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprTrueNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use Rector\BetterPhpDocParser\PhpDoc\DoctrineAnnotationTagValueNode;
use Rector\BetterPhpDocParser\PhpDoc\StringNode;
use Rector\BetterPhpDocParser\PhpDocParser\ClassAnnotationMatcher;
use Rector\BetterPhpDocParser\PhpDocParser\StaticDoctrineAnnotationParser;
use Rector\BetterPhpDocParser\ValueObject\Parser\BetterTokenIterator;
use Rector\BetterPhpDocParser\ValueObject\PhpDocAttributeKey;

final class PlainValueParser
{
    private StaticDoctrineAnnotationParser $staticDoctrineAnnotationParser;

    private ArrayParser $arrayParser;

    public function __construct(
        private readonly ClassAnnotationMatcher $classAnnotationMatcher,
    ) {
    }

    public function autowire(
        StaticDoctrineAnnotationParser $staticDoctrineAnnotationParser,
        ArrayParser $arrayParser
    ): void {
        $this->staticDoctrineAnnotationParser = $staticDoctrineAnnotationParser;
        $this->arrayParser = $arrayParser;
    }

    /**
     * @return string|mixed[]|ConstExprNode|DoctrineAnnotationTagValueNode|StringNode
     */
    public function parseValue(
        BetterTokenIterator $tokenIterator,
        Node $currentPhpNode
    ): string | array | ConstExprNode | DoctrineAnnotationTagValueNode | StringNode {
        $currentTokenValue = $tokenIterator->currentTokenValue();

        // temporary hackaround multi-line doctrine annotations
        if ($tokenIterator->isCurrentTokenType(Lexer::TOKEN_END)) {
            return $currentTokenValue;
        }

        // consume the token
        $isOpenCurlyArray = $tokenIterator->isCurrentTokenType(Lexer::TOKEN_OPEN_CURLY_BRACKET);
        if ($isOpenCurlyArray) {
            return $this->arrayParser->parseCurlyArray($tokenIterator, $currentPhpNode);
        }

        $tokenIterator->next();

        // normalize value
        $constExprNode = $this->matchConstantValue($currentTokenValue);
        if ($constExprNode instanceof ConstExprNode) {
            return $constExprNode;
        }

        $currentTokenValue = $this->parseStringValue($tokenIterator, $currentTokenValue);

        // nested entity!, supported in attribute since PHP 8.1
        if ($tokenIterator->isCurrentTokenType(Lexer::TOKEN_OPEN_PARENTHESES)) {
            return $this->parseNestedDoctrineAnnotationTagValueNode(
                $currentTokenValue,
                $tokenIterator,
                $currentPhpNode
            );
        }

        $start = $tokenIterator->currentPosition();

        // from "quote to quote"
        if ($currentTokenValue === '"') {
            do {
                $tokenIterator->next();
            } while (! str_contains($tokenIterator->currentTokenValue(), '"'));
        }

        $end = $tokenIterator->currentPosition();
        if ($start + 1 < $end) {
            return new StringNode($tokenIterator->printFromTo($start, $end));
        }

        return $currentTokenValue;
    }

    private function parseStringValue(BetterTokenIterator $tokenIterator, string $currentTokenValue): string
    {
        if (
            // start with a quote but doesn't end with one
            (str_starts_with($currentTokenValue, '"') && ! str_ends_with($currentTokenValue, '"'))
        ) {
            $currentTokenValue = $this->parseMultilineOrWhiteSpacedString($tokenIterator, $currentTokenValue);
        } else {
            while ($tokenIterator->isCurrentTokenType(Lexer::TOKEN_DOUBLE_COLON) ||
                $tokenIterator->isCurrentTokenType(Lexer::TOKEN_IDENTIFIER)
            ) {
                $currentTokenValue .= $tokenIterator->currentTokenValue();
                $tokenIterator->next();
            }
        }
        return $currentTokenValue;
    }

    private function parseMultilineOrWhiteSpacedString(
        BetterTokenIterator $tokenIterator,
        string $currentTokenValue
    ): string {
        while (str_starts_with($currentTokenValue, '"') && ! str_ends_with($currentTokenValue, '"')) {
            if (! $tokenIterator->isCurrentTokenType(Lexer::TOKEN_PHPDOC_EOL)) {
                $currentTokenValue .= ' ';
            }
            if (str_starts_with($currentTokenValue, '"') && str_contains(
                $tokenIterator->currentTokenValue(),
                '"'
            ) && $currentTokenValue !== $tokenIterator->currentTokenValue()) {
                //starts with '"' and current token contains '"', should be the end
                $currentTokenValue .= substr(
                    $tokenIterator->currentTokenValue(),
                    0,
                    ((int) strpos($tokenIterator->currentTokenValue(), '"') + 1)
                );
                $tokenIterator->next();
                break;
            }
            $currentTokenValue .= $tokenIterator->currentTokenValue();
            $tokenIterator->next();
        }

        if (str_starts_with($currentTokenValue, '"') && str_ends_with($currentTokenValue, '"')) {
            $currentTokenValue = trim(str_replace('"', '', $currentTokenValue));
        }

        return $currentTokenValue;
    }

    private function parseNestedDoctrineAnnotationTagValueNode(
        string $currentTokenValue,
        BetterTokenIterator $tokenIterator,
        Node $currentPhpNode
    ): DoctrineAnnotationTagValueNode {
        // @todo
        $annotationShortName = $currentTokenValue;
        $values = $this->staticDoctrineAnnotationParser->resolveAnnotationMethodCall($tokenIterator, $currentPhpNode);

        $fullyQualifiedAnnotationClass = $this->classAnnotationMatcher->resolveTagFullyQualifiedName(
            $annotationShortName,
            $currentPhpNode
        );

        // keep the last ")"
        $tokenIterator->tryConsumeTokenType(Lexer::TOKEN_PHPDOC_EOL);

        if ($tokenIterator->currentTokenValue() === ')') {
            $tokenIterator->consumeTokenType(Lexer::TOKEN_CLOSE_PARENTHESES);
        }

        // keep original name to differentiate between short and FQN class
        $identifierTypeNode = new IdentifierTypeNode($annotationShortName);
        $identifierTypeNode->setAttribute(PhpDocAttributeKey::RESOLVED_CLASS, $fullyQualifiedAnnotationClass);

        return new DoctrineAnnotationTagValueNode($identifierTypeNode, $annotationShortName, $values);
    }

    private function matchConstantValue(string $currentTokenValue): ConstExprNode | null
    {
        if (strtolower($currentTokenValue) === 'false') {
            return new ConstExprFalseNode();
        }

        if (strtolower($currentTokenValue) === 'true') {
            return new ConstExprTrueNode();
        }

        if (! is_numeric($currentTokenValue)) {
            return null;
        }

        if ((string) (int) $currentTokenValue !== $currentTokenValue) {
            return null;
        }

        return new ConstExprIntegerNode($currentTokenValue);
    }
}
