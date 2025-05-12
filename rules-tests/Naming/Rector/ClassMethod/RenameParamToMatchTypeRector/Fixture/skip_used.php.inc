<?php

namespace Rector\Tests\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector\Fixture;

final class SkipUsed
{
    public function run(Node $and)
    {
        $this->betterNodeFinder->find($and->stmts, function (Node $node) use ($and) {
              return $and !== $node;
        });
    }
}
