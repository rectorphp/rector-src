<?php

declare(strict_types=1);

namespace Rector\PhpParser\Parser;

use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Scalar\Encapsed;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\PhpParser\Printer\BetterStandardPrinter;
use Rector\Util\StringUtils;

final readonly class InlineCodeParser
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
        private BetterStandardPrinter $betterStandardPrinter,
        private SimplePhpParser $simplePhpParser,
        private ValueResolver $valueResolver,
    ) {
    }

    /**
     * @return Stmt[]
     *
     * @api
     * @deprecated use parseFile() or parseString() instead
     */
    public function parse(string $content): array
    {
        // to cover files too
        if (is_file($content)) {
            $content = FileSystem::read($content);
        }

        return $this->parseCode($content);
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

        if ($concat->left instanceof Concat) {
            if ($concat->left->left instanceof String_ && ! $concat->left->right instanceof String_) {
                $trimLeftValue = trim($concat->left->left->value);
                if (str_ends_with($trimLeftValue, ')')) {
                    $concat->left->left->value .= '.';
                }
            }

            if (! $concat->left->right instanceof String_ && $concat->right instanceof String_) {
                $firstChar = trim($concat->right->value)[0] ?? '';
                if (! in_array($firstChar, [')', '(', '"', "'", '\\', '.', ';'], true)) {
                    $concat->right->value = '.' . $concat->right->value;
                }
            }
        }

        $string = $this->stringify($concat->left) . $this->stringify($concat->right);
        return Strings::replace(
            $string,
            self::VARIABLE_IN_SINGLE_QUOTED_REGEX,
            static fn (array $match) => $match['variable']
        );
    }
}
