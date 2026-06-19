<?php

declare(strict_types=1);

namespace Rector\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\ComplexType;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;

final class ScopeAnalyzer
{
    /**
     * @var array<class-string<Node>>
     */
    private const array NON_REFRESHABLE_NODES = [Name::class, Identifier::class, ComplexType::class];

    public function isRefreshable(Node $node): bool
    {
<<<<<<< HEAD
        return array_all(
            self::NON_REFRESHABLE_NODES,
            fn (string $noScopeNode): bool => ! $node instanceof $noScopeNode
        );
=======
        return array_all(self::NON_REFRESHABLE_NODES, fn ($noScopeNode): bool => ! $node instanceof $noScopeNode);
>>>>>>> 424f600506 ([php] bump to PHP 8.4 syntax)
    }
}
