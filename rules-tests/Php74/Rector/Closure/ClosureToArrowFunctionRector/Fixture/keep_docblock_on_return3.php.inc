<?php

class KeepDocblockOnReturn3
{
    public function run()
    {
        function() {
            /**
             * comment
             */
            // something
            // @psalm-suppress UndefinedFunction
            return ff();
        };
    }
}

?>
-----
<?php

class KeepDocblockOnReturn3
{
    public function run()
    {
        fn() =>
            /**
             * comment
             */
            // something
            // @psalm-suppress UndefinedFunction
            ff();
    }
}

?>
