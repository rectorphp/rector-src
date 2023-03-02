<?php

switch ($file['type']) {
    case 'directory':
        $root['folders'][] = $file;
        break;
    case 'file':
        $root['files'][] = $file;
        break;
    default:
        $root['unknown'][] = $file;
}

?>
