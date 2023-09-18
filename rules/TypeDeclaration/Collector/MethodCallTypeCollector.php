<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Collector;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;

final class MethodCallTypeCollector implements Collector
{
    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    /**
     * @return int[]|null
     */
    public function processNode(Node $node, Scope $scope): ?array
    {
        return [1, 2];
    }
}
