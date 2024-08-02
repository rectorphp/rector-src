<?php

class SkipNoNamespaceConflictDocblockShortname
{
    /**
     * @param Rector\Tests\Issues\AutoImport\Source\stdClass
     */
    public function some($param)
    {
    }

    /**
     * @param stdClass
     */
    public function some2($param)
    {
    }
}