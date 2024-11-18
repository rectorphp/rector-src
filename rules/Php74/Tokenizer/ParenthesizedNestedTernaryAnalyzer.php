<?php

declare(strict_types=1);

namespace Rector\Php74\Tokenizer;

use PhpParser\Node\Expr\Ternary;
use Rector\ValueObject\Application\File;

final class ParenthesizedNestedTernaryAnalyzer
{
    public function isParenthesized(File $file, Ternary $ternary): bool
    {
        $oldTokens = $file->getOldTokens();
        $startTokenPos = $ternary->getStartTokenPos();
        $endTokenPos = $ternary->getEndTokenPos();

        $hasOpenParentheses = isset($oldTokens[$startTokenPos]) && (string) $oldTokens[$startTokenPos] === '(';
        $hasCloseParentheses = isset($oldTokens[$endTokenPos]) && (string) $oldTokens[$endTokenPos] === ')';

        return $hasOpenParentheses || $hasCloseParentheses;
    }
}
