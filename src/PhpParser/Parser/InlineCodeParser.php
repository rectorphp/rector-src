<?php

declare(strict_types=1);

namespace Rector\Core\PhpParser\Parser;

use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Scalar\Encapsed;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt;
use Rector\Core\PhpParser\Node\Value\ValueResolver;
use Rector\Core\PhpParser\Printer\BetterStandardPrinter;
use Rector\Core\Util\StringUtils;

final class InlineCodeParser
{
    /**
     * @var string
     * @see https://regex101.com/r/dwe4OW/1
     */
    private const PRESLASHED_DOLLAR_REGEX = '#\\\\\$#';

    /**
     * @var string
     * @see https://regex101.com/r/tvwhWq/1
     */
    private const CURLY_BRACKET_WRAPPER_REGEX = "#'{(\\\$.*?)}'#";

    /**
     * @var string
     * @see https://regex101.com/r/TBlhoR/1
     */
    private const OPEN_PHP_TAG_REGEX = '#^\<\?php\s+#';

    /**
     * @var string
     * @see https://regex101.com/r/TUWwKw/1/
     */
    private const ENDING_SEMI_COLON_REGEX = '#;(\s+)?$#';

    /**
     * @var string
     * @see https://regex101.com/r/8fDjnR/1
     */
    private const VARIABLE_IN_SINGLE_QUOTED_REGEX = '#\'(?<variable>\$.*)\'#U';

    /**
     * @var string
     * @see https://regex101.com/r/1lzQZv/1
     */
    private const BACKREFERENCE_NO_QUOTE_REGEX = '#(?<!")(?<backreference>\\\\\d+)(?!")#';

    /**
     * @var string
     * @see https://regex101.com/r/nSO3Eq/1
     */
    private const BACKREFERENCE_NO_DOUBLE_QUOTE_START_REGEX = '#(?<!")(?<backreference>\$\d+)#';

    public function __construct(
        private readonly BetterStandardPrinter $betterStandardPrinter,
        private readonly SimplePhpParser $simplePhpParser,
        private readonly ValueResolver $valueResolver,
    ) {
    }

    /**
     * @return Stmt[]
     */
    public function parse(string $content): array
    {
        // to cover files too
        if (is_file($content)) {
            $content = FileSystem::read($content);
        }

        // wrap code so php-parser can interpret it
        $content = StringUtils::isMatch($content, self::OPEN_PHP_TAG_REGEX) ? $content : '<?php ' . $content;
        $content = StringUtils::isMatch($content, self::ENDING_SEMI_COLON_REGEX) ? $content : $content . ';';

        return $this->simplePhpParser->parseString($content);
    }

    public function stringify(Expr $expr): string
    {
        if ($expr instanceof String_) {
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

        if ($expr instanceof Encapsed) {
            return $this->resolveEncapsedValue($expr);
        }

        if ($expr instanceof Concat) {
            return $this->resolveConcatValue($expr);
        }

        return $this->betterStandardPrinter->print($expr);
    }

    private function resolveEncapsedValue(Encapsed $encapsed): string
    {
        $value = '';
        $isRequirePrint = false;
        foreach ($encapsed->parts as $part) {
            $partValue = (string) $this->valueResolver->getValue($part);
            if (str_ends_with($partValue, "'")) {
                $isRequirePrint = true;
                break;
            }

            $value .= $partValue;
        }

        $printedExpr = $isRequirePrint ? $this->betterStandardPrinter->print($encapsed) : $value;

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
            static fn (array $match) => $match['variable']
        );
    }
}
