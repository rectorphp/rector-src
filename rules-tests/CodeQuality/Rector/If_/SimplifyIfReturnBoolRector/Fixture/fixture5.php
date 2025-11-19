<?php

function simplifyIfReturnBool5()
{
    if (!strpos($docToken->getContent(), "\n")) {
        return false;
    }

    return true;
}

?>
