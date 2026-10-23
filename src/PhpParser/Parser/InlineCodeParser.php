<?php

declare(strict_types=1);

namespace Rector\PhpParser\Parser;

use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Scalar\InterpolatedString;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\PhpParser\Printer\BetterStandardPrinter;
use Rector\Util\StringUtils;

final readonly class InlineCodeParser
{
    /**
     * @see https://regex101.com/r/dwe4OW/1
     */
    private const string PRESLASHED_DOLLAR_REGEX = '#\\\\\$#';

    /**
     * @see https://regex101.com/r/tvwhWq/1
     */
    private const string CURLY_BRACKET_WRAPPER_REGEX = "#'{(\\\$.*?)}'#";

    /**
     * @see https://regex101.com/r/TBlhoR/1
     */
    private const string OPEN_PHP_TAG_REGEX = '#^\<\?php\s+#';

    /**
     * @see https://regex101.com/r/TUWwKw/1/
     */
    private const string ENDING_SEMI_COLON_REGEX = '#;(\s+)?$#';

    /**
     * @see https://regex101.com/r/8fDjnR/1
     */
    private const string VARIABLE_IN_SINGLE_QUOTED_REGEX = '#\'(?<variable>\$.*)\'#U';

    /**
     * @see https://regex101.com/r/1lzQZv/1
     */
    private const string BACKREFERENCE_NO_QUOTE_REGEX = '#(?<!")(?<backreference>\\\\\d+)(?!")#';

    /**
     * @see https://regex101.com/r/nSO3Eq/1
     */
    private const string BACKREFERENCE_NO_DOUBLE_QUOTE_START_REGEX = '#(?<!")(?<backreference>\$\d+)#';

    /**
     * @see https://regex101.com/r/13mVVg/1
     */
    private const string HEX_BACKREFERENCE_REGEX = '#0x(?<backreference>\$\d+)#';

    public function __construct(
        private BetterStandardPrinter $betterStandardPrinter,
        private SimplePhpParser $simplePhpParser,
        private ValueResolver $valueResolver,
    ) {
    }

    /**
     * @api downgrade
     *
     * @return Stmt[]
     */
    public function parseFile(string $fileName): array
    {
        $fileContent = FileSystem::read($fileName);
        return $this->parseCode($fileContent);
    }

    /**
     * @return Stmt[]
     */
    public function parseString(string $fileContent): array
    {
        return $this->parseCode($fileContent);
    }

    public function stringify(Expr $expr): string
    {
        if ($expr instanceof String_) {
            if (! str_contains($expr->value, "'") && ! str_contains($expr->value, '"') && StringUtils::isMatch(
                $expr->value,
                self::HEX_BACKREFERENCE_REGEX
            )) {
                return Strings::replace(
                    $expr->value,
                    self::HEX_BACKREFERENCE_REGEX,
                    static function (array $match): string {
                        $number = ltrim((string) $match['backreference'], '\\$');
                        return 'hexdec($matches[' . $number . '])';
                    }
                );
            }

            if (! StringUtils::isMatch($expr->value, self::BACKREFERENCE_NO_QUOTE_REGEX)) {
                return Strings::replace(
                    $expr->value,
                    self::BACKREFERENCE_NO_DOUBLE_QUOTE_START_REGEX,
                    static fn (array $match): string => '"' . $match['backreference'] . '"'
                );
            }

            return Strings::replace(
                $expr->value,
                self::BACKREFERENCE_NO_QUOTE_REGEX,
                static fn (array $match): string => '"\\' . $match['backreference'] . '"'
            );
        }

        if ($expr instanceof InterpolatedString) {
            return $this->resolveEncapsedValue($expr);
        }

        if ($expr instanceof Concat) {
            return $this->resolveConcatValue($expr);
        }

        return $this->betterStandardPrinter->print($expr);
    }

    /**
     * @return Stmt[]
     */
    private function parseCode(string $code): array
    {
        // wrap code so php-parser can interpret it
        $code = StringUtils::isMatch($code, self::OPEN_PHP_TAG_REGEX) ? $code : '<?php ' . $code;
        $code = StringUtils::isMatch($code, self::ENDING_SEMI_COLON_REGEX) ? $code : $code . ';';

        return $this->simplePhpParser->parseString($code);
    }

    private function resolveEncapsedValue(InterpolatedString $interpolatedString): string
    {
        $value = '';
        $isRequirePrint = false;
        foreach ($interpolatedString->parts as $part) {
            $partValue = (string) $this->valueResolver->getValue($part);
            if (str_ends_with($partValue, "'")) {
                $isRequirePrint = true;
                break;
            }

            $value .= $partValue;
        }

        $printedExpr = $isRequirePrint ? $this->betterStandardPrinter->print($interpolatedString) : $value;

        // remove "
        $printedExpr = trim($printedExpr, '""');

        // use \$ → $
        $printedExpr = Strings::replace($printedExpr, self::PRESLASHED_DOLLAR_REGEX, '$');
        // use \'{$...}\' → $...
        return Strings::replace($printedExpr, self::CURLY_BRACKET_WRAPPER_REGEX, '$1');
    }

    private function resolveConcatValue(Concat $concat): string
    {
        if ($concat->left instanceof Concat &&
            $concat->right instanceof String_ && str_starts_with($concat->right->value, '$')) {
            $concat->right->value = '.' . $concat->right->value;
        }

        if ($concat->right instanceof String_ && str_starts_with($concat->right->value, '($')) {
            $concat->right->value .= '.';
        }

        $string = $this->stringify($concat->left) . $this->stringify($concat->right);
        return Strings::replace(
            $string,
            self::VARIABLE_IN_SINGLE_QUOTED_REGEX,
            static fn (array $match): string => (string) $match['variable']
        );
    }
}
