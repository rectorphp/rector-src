<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Tests\Rule\NoScopeFetchBeforeCheapGuardRule\Source;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\PHPStan\ScopeFetcher;

final class ScopeFetchBeforeGuardRector
{
    public function refactor(ClassMethod $node): ?Node
    {
        $scope = ScopeFetcher::fetch($node);
        if ($node->returnType instanceof Node) {
            return null;
        }

        return $node;
    }
}
