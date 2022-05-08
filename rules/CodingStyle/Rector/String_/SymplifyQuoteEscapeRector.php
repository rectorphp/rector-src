<?php

declare(strict_types=1);

namespace Rector\CodingStyle\Rector\String_;

use PhpParser\Node;
use PhpParser\Node\Scalar\String_;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\Util\StringUtils;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodingStyle\Rector\String_\SymplifyQuoteEscapeRector\SymplifyQuoteEscapeRectorTest
 */
final class SymplifyQuoteEscapeRector extends AbstractRector
{
    /**
     * @var string
     * @see https://regex101.com/r/qEkCe9/2
     */
    private const ESCAPED_CHAR_REGEX = '#\\\\|\$|\\n|\\t#sim';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Prefer quote that are not inside the string',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
         $name = "\" Tom";
         $name = '\' Sara';
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
         $name = '" Tom';
         $name = "' Sara";
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [String_::class];
    }

    /**
     * @param String_ $node
     */
    public function refactor(Node $node): ?String_
    {
        $doubleQuoteCount = substr_count($node->value, '"');
        $singleQuoteCount = substr_count($node->value, "'");
        $kind = $node->getAttribute(AttributeKey::KIND);
        $hasChangedSingleQuoted = false;
        $hasChangedDoubleQuoted = false;

        if ($kind === String_::KIND_SINGLE_QUOTED) {
            $hasChangedSingleQuoted = $this->processSingleQuoted($node, $doubleQuoteCount, $singleQuoteCount);
        }

        $quoteKind = $node->getAttribute(AttributeKey::KIND);
        if ($quoteKind === String_::KIND_DOUBLE_QUOTED) {
            $hasChangedDoubleQuoted = $this->processDoubleQuoted($node, $singleQuoteCount, $doubleQuoteCount);
        }

        if ($hasChangedSingleQuoted) {
            return $node;
        }

        if ($hasChangedDoubleQuoted) {
            return $node;
        }

        return null;
    }

    private function processSingleQuoted(String_ $string, int $doubleQuoteCount, int $singleQuoteCount): bool
    {
        if ($doubleQuoteCount === 0 && $singleQuoteCount > 0) {
            // contains chars that will be newly escaped
            if ($this->isMatchEscapedChars($string->value)) {
                return false;
            }

            $string->setAttribute(AttributeKey::KIND, String_::KIND_DOUBLE_QUOTED);
            // invoke override
            $string->setAttribute(AttributeKey::ORIGINAL_NODE, null);

            return true;
        }

        return false;
    }

    private function processDoubleQuoted(String_ $string, int $singleQuoteCount, int $doubleQuoteCount): bool
    {
        if ($singleQuoteCount === 0 && $doubleQuoteCount > 0) {
            // contains chars that will be newly escaped
            if ($this->isMatchEscapedChars($string->value)) {
                return false;
            }

            $string->setAttribute(AttributeKey::KIND, String_::KIND_SINGLE_QUOTED);
            // invoke override
            $string->setAttribute(AttributeKey::ORIGINAL_NODE, null);

            return true;
        }

        return false;
    }

    private function isMatchEscapedChars(string $string): bool
    {
        return StringUtils::isMatch($string, self::ESCAPED_CHAR_REGEX);
    }
}
