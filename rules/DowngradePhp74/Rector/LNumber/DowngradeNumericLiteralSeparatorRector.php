<?php

declare(strict_types=1);

namespace Rector\DowngradePhp74\Rector\LNumber;

use PhpParser\Node;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Scalar\LNumber;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://wiki.php.net/rfc/numeric_literal_separator
 *
 * @see \Rector\Tests\DowngradePhp74\Rector\LNumber\DowngradeNumericLiteralSeparatorRector\DowngradeNumericLiteralSeparatorRectorTest
 */
final class DowngradeNumericLiteralSeparatorRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove "_" as thousands separator in numbers',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $int = 1_000;
        $float = 1_000_500.001;
    }
}
CODE_SAMPLE
,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $int = 1000;
        $float = 1000500.001;
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
        return [LNumber::class, DNumber::class];
    }

    /**
     * @param LNumber|DNumber $node
     */
    public function refactor(Node $node): ?Node
    {
        $node->getAttribute(AttributeKey::RAW_VALUE)

        if ($this->shouldSkip($node)) {
            return null;
        }

        $numberValueAsString = (string) $node->value;
        if (\str_contains($numberValueAsString, '+')) {
            return null;
        }

        // trigger reprint
        $node->setAttribute(AttributeKey::ORIGINAL_NODE, null);

        if ($node instanceof LNumber) {
            return $node;
        }

        /**
         * This code follows a guess, to avoid modifying floats needlessly.
         * If the node is a float, but it doesn't contain ".",
         * then it's likely that the number was forced to be a float
         * by adding ".0" at the end (eg: 0.0).
         * Then, add it again.
         */
        if (! \str_contains($numberValueAsString, '.')) {
            $numberValueAsString .= '.0';
        }

        $node->value = (float) $numberValueAsString;

        return $node;
    }

    private function shouldSkip(LNumber | DNumber $node): bool
    {
        // "_" notation can be applied to decimal numbers only
        if ($node instanceof LNumber) {
            $numberKind = $node->getAttribute(AttributeKey::KIND);
            if ($numberKind !== LNumber::KIND_DEC) {
                return true;
            }
        }

        // we have to hack around tokens to get original value, see https://github.com/nikic/PHP-Parser/pull/832
        $oldTokens = $this->file->getOldTokens();
        $tokenValue = $oldTokens[$node->getStartTokenPos()][1] ?? null;

        if ($tokenValue === null) {
            return true;
        }

        return ! str_contains((string) $tokenValue, '_');
    }
}
