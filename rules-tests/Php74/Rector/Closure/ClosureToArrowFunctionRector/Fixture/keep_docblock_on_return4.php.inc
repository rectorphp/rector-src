<?php

class KeepDocblockOnReturn4
{
    public function run()
    {
        function deep() {
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
}

?>
-----
<?php

class KeepDocblockOnReturn4
{
    public function run()
    {
        function deep() {
            fn() =>
                /**
                 * comment
                 */
                // something
                // @psalm-suppress UndefinedFunction
                ff();
        }
    }
}

?>
