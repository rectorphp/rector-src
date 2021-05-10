<?php

declare(strict_types=1);

namespace Rector\Php55;

use Nette\Utils\Strings;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Scalar\String_;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\PhpParser\Node\Value\ValueResolver;

final class RegexMatcher
{
    /**
     * @var string
     * @see https://regex101.com/r/Ok4wuE/1
     */
    private const LAST_E_REGEX = '#(\w+)?e(\w+)?$#';

    /**
     * @var string
     * @see https://regex101.com/r/2NWVwT/1
     */
    private const LETTER_SUFFIX_REGEX = '#(?<modifiers>\w+)$#';

    public function __construct(
        private ValueResolver $valueResolver
    ) {
    }

    public function resolvePatternExpressionWithoutEIfFound(Expr $expr): ?Expr
    {
        if ($expr instanceof String_) {
            $pattern = $this->valueResolver->getValue($expr);

            if (! is_string($pattern)) {
                return null;
            }

            $delimiter = $pattern[0];
            if (! is_string($delimiter)) {
                throw new ShouldNotHappenException();
            }

            /** @var string $modifiers */
            $modifiers = Strings::after($pattern, $delimiter, -1);
            if (! Strings::contains($modifiers, 'e')) {
                return null;
            }

            $patternWithoutE = $this->createPatternWithoutE($pattern, $delimiter, $modifiers);
            return new String_($patternWithoutE);
        }

        if ($expr instanceof Concat) {
            return $this->matchConcat($expr);
        }

        return null;
    }

    private function createPatternWithoutE(string $pattern, string $delimiter, string $modifiers): string
    {
        $modifiersWithoutE = Strings::replace($modifiers, '#e#', '');

        return Strings::before($pattern, $delimiter, -1) . $delimiter . $modifiersWithoutE;
    }

    private function matchConcat(Concat $concat): ?Expr
    {
        $lastItem = $concat->right;
        if (! $lastItem instanceof String_) {
            return null;
        }

        $matches = Strings::match($lastItem->value, self::LETTER_SUFFIX_REGEX);
        if (! isset($matches['modifiers'])) {
            return null;
        }

        if (! Strings::contains($matches['modifiers'], 'e')) {
            return null;
        }

        // replace last "e" in the code
        $lastItem->value = Strings::replace($lastItem->value, self::LAST_E_REGEX, '$1$2');

        return $concat;
    }
}
