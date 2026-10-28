<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture;

final class SkipDocblockSameNamespaceClass
{
    /**
     * @param Countable $countable
     * @return array<Countable>
     */
    public function run($countable)
    {
    }
}
