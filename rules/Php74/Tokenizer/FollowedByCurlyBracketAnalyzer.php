<?php

declare(strict_types=1);

namespace Rector\Php74\Tokenizer;

use PhpParser\Node;
use Rector\Core\ValueObject\Application\File;

final class FollowedByCurlyBracketAnalyzer
{
    public function isFollowed(File $file, Node $node): bool
    {
        $oldTokens = $file->getOldTokens();
        $endTokenPost = $node->getEndTokenPos();

        return isset($oldTokens[$endTokenPost]) && $oldTokens[$endTokenPost] === '}';
    }
}
