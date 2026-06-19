<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector\Fixture;

final class SkipNullResetOfInternalObject
{
    public function run(string $path): void
    {
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        echo $finfo->file($path);

        $finfo = null;
    }
}
