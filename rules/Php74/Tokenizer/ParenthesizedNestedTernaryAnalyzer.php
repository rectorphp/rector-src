<?php

declare(strict_types=1);

namespace Rector\Php74\Tokenizer;

use PhpParser\Node;
use Rector\Core\ValueObject\Application\File;

final class ParenthesizedNestedTernaryAnalyzer
{
    public function isParenthesized(File $file, Node $node): bool
    {
        $oldTokens = $file->getOldTokens();
        $startTokenPos = $node->getStartTokenPos();

        return isset($oldTokens[$startTokenPos]) && $oldTokens[$startTokenPos] === '(';
    }
}
