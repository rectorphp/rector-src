<?php

namespace Rector\Tests\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector\Fixture;

final class SkipUsed
{
    public function run(Node $node)
    {
        $this->betterNodeFinder->find($node->stmts, function (Node $_node) use ($node) {
              return $node !== $_node;
        });
    }
}
