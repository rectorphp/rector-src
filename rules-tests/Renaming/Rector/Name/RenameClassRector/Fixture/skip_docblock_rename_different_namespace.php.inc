<?php

use \Company2\Bar;

final class SkipDocblockRenameDifferentNamespace
{
    /**
     * Referencing Bar in \Company2
     * @param Bar $foo
     */
    public function importedClassesReferencedViaAlias($foo)
    {
    }
}
