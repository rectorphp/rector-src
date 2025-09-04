<?php

final class DontRenameDifferentNamespacedFunction
{
    public function run(): void
    {
        $addressParts = Psl\Str\split($addressLine, $houseNumber);
    }
}

?>
-----
<?php

use Psl\Str\split;

final class DontRenameDifferentNamespacedFunction
{
    public function run(): void
    {
        $addressParts = split($addressLine, $houseNumber);
    }
}

?>