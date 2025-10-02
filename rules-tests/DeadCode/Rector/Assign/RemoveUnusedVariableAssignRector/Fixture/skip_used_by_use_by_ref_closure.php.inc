<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector\Fixture;

function skipUsedByUseByRefClosure(Throwable $signalingException)
{
    $prevErrorHandler = set_error_handler(function ($type, $msg, $file, $line, $context = []) use (&$prevErrorHandler, $signalingException) {
        if ($file === __FILE__) {
            throw $signalingException;
        }

        return $prevErrorHandler !== null ? $prevErrorHandler($type, $msg, $file, $line, $context) : false;
    });
}

?>
