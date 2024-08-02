<?php

class SkipNoNamespaceConflictDocblockShortname
{
    /**
     * @param Rector\Tests\Issues\AutoImport\Source\stdClass $param
     */
    public function some($param)
    {
    }

    /**
     * @param stdClass $param
     */
    public function some2($param)
    {
    }
}