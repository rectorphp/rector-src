<?php

class KeepDocblockOnReturn
{
    public function run()
    {
        function() {
            /** @psalm-suppress UndefinedFunction */
            return ff();
        };
    }
}

?>
-----
<?php

class KeepDocblockOnReturn
{
    public function run()
    {
        fn() =>
            /** @psalm-suppress UndefinedFunction */
            ff();
    }
}

?>
