<?php

if ($f = false and true) {
    return $f;
}

if ($f = true and false) {
    return $f;
}

if (false and true) {
    return $f;
}

?>
-----
<?php

if (($f = false) && true) {
    return $f;
}

if (($f = true) && false) {
    return $f;
}

if (false && true) {
    return $f;
}

?>
