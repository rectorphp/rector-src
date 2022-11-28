<?php

namespace Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveJustPropertyFetchRector\Fixture;

final class SkipConcatAssign
{
    public function run()
    {
        $assignedObject = json_decode('some...');

        $error = $assignedObject->message;
        $error .= "\n";

        return $error;
    }
}
