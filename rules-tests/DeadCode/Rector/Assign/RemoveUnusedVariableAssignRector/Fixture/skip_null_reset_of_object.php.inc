<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector\Fixture;

final class SkipNullResetOfObject
{
    public function process(string $path): bool
    {
        try {
            $file = new \SplFileObject($path);
        } catch (\Exception $e) {
            return false;
        }

        $file->seek(0);
        echo $file->current();

        // Close the file
        $file = null;

        return true;
    }
}
