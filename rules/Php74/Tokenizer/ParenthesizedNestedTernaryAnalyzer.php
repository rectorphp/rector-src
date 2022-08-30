<?php

declare(strict_types=1);

namespace Rector\Php74\Tokenizer;

use PhpParser\Node\Expr\Ternary;
use Rector\Core\ValueObject\Application\File;

final class ParenthesizedNestedTernaryAnalyzer
{
    public function isParenthesized(File $file, Ternary $node): bool
    {
        $oldTokens = $file->getOldTokens();
        $startTokenPos = $node->getStartTokenPos();

        return isset($oldTokens[$startTokenPos]) && $oldTokens[$startTokenPos] === '(';
    }
}
