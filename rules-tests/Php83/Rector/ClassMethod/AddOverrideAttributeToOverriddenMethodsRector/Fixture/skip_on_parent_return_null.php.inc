<?php

declare(strict_types=1);

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Fixture;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

final class SkipOnParentReturnNull extends NodeVisitorAbstract
{
    public function enterNode(Node $node)
    {
        return 1;
    }
}
