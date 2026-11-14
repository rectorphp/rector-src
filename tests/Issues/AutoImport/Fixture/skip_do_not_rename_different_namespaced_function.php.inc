<?php

final class DontRenameDifferentNamespacedFunction
{
    public function run(): void
    {
        $addressParts = Psl\Str\split($addressLine, $houseNumber);
    }
}

?>
