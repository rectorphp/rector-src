<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Tests\Rule\NoScopeFetchBeforeCheapGuardRule\Source;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\PHPStan\ScopeFetcher;

final class SkipScopeFetchRector
{
    public function refactor(ClassMethod $node): ?Node
    {
        // cheap guard already runs first
        if ($node->returnType instanceof Node) {
            return null;
        }

        $scope = ScopeFetcher::fetch($node);
        if (! $scope->isInClass()) {
            return null;
        }

        return $node;
    }
}
