<?php

function skipNamedArgument($str){
    json_decode($str, flags: JSON_THROW_ON_ERROR);
}

?>
