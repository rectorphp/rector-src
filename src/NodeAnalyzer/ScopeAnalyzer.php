<?php

declare(strict_types=1);

namespace Rector\Core\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Namespace_;
use Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace;

final class ScopeAnalyzer
{
    public function hasScope(Node $node): bool
    {
        return $node instanceof Name || $node instanceof Namespace_ || $node instanceof FileWithoutNamespace || $node instanceof Identifier;
    }
}
