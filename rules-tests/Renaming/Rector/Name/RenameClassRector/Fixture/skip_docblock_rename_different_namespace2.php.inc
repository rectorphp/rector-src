<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture;

final class SkipDocblockRenameDifferentNamespace2
{
    /**
     * Referencing Bar in same namespace
     * BUG: THIS is replaced, likely because it has the same alias as "Bar" from root.
     * Real class of this is \CompanyA\Bar, not \Bar
     *
     * @param Bar $foo
     */
    public function importedClassViaSameNamespace($foo)
    {
    }
}
