<?php

function simplifyForeachToCoalescing2($currentFunction)
{
    $oldToNewFunctions = ['a' => 'b'];
    foreach ($oldToNewFunctions as $oldFunction => $newFunction) {
        if ($oldFunction === $currentFunction) {
            return $newFunction;
        }
    }

    return 45;
}

?>
-----
<?php

function simplifyForeachToCoalescing2($currentFunction)
{
    $oldToNewFunctions = ['a' => 'b'];
    return $oldToNewFunctions[$currentFunction] ?? 45;
}

?>
