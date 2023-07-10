<?php

class KeepDocblockOnReturn2
{
    public function run()
    {
        function() {
            // @psalm-suppress UndefinedFunction
            return ff();
        };
    }
}

?>
-----
<?php

class KeepDocblockOnReturn2
{
    public function run()
    {
        fn() =>
            // @psalm-suppress UndefinedFunction
            ff();
    }
}

?>
