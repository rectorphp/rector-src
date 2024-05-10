<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\Fixture;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;

final class SkipMoreDetailType
{
    /**
     * @param Name $node
     */
    public function run(Node $node)
    {
        if ($node instanceof FullyQualified) {
            return;
        }

        $this->execute($node);
    }

    private function execute(Name $node)
    {
    }
}
