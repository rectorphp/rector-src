<?php

declare(strict_types=1);

namespace Rector\Core\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Namespace_;
use Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace;
use PHPStan\Analyser\MutatingScope;

final class ScopeAnalyzer
{
    /**
     * @var array<class-string<Node>>
     */
    private const NO_SCOPE_NODES = [Name::class, Identifier::class, Param::class, Arg::class];

    public function hasScope(Node $node): bool
    {
        foreach (self::NO_SCOPE_NODES as $noScopeNode) {
            if ($node instanceof $noScopeNode) {
                return false;
            }
        }

        return true;
    }

    public function isScopeResolvableFromFile(Node $node, ?MutatingScope $mutatingScope = null): bool
    {
        if ($mutatingScope instanceof MutatingScope) {
            return false;
        }

        return in_array($node::class, [Namespace_::class, FileWithoutNamespace::class], true);
    }
}
