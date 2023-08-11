<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\ForeachItemsAssignToEmptyArrayToAssignRector\Fixture;

use PhpParser\Node;
use PHPStan\Analyser\MutatingScope;

class SkipForeachAssign
{
    /**
     * @var mixed[]
     */
    private $files = [];

    public function processNodes()
    {
        $this->files = [];
    }

    private function resolveDependentFiles(
        SomeDependency $someDependency,
        Node $node,
        MutatingScope $mutatingScope
    ): void {
        foreach ($someDependency->resolve($node, $mutatingScope) as $file) {
            $this->files[] = $file;
        }
    }
}
