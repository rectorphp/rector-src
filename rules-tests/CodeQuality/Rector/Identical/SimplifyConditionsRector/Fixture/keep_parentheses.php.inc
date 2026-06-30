<?php

function keepParentheses()
{
    if (! (1 < (1 ? 2 : 1))) {
    }
}

?>
-----
<?php

function keepParentheses()
{
    if (1 >= (1 ? 2 : 1)) {
    }
}

?>
