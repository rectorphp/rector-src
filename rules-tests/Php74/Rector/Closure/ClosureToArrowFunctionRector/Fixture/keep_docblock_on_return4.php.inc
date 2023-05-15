<?php

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
?>
-----
<?php

function deep() {
    fn() =>
        /**
         * comment
         */
        // something
        // @psalm-suppress UndefinedFunction
        ff();
}
?>
