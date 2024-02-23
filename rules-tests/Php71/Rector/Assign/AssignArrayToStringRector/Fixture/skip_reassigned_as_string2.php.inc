<?php

// not inside function with direct check on purpose

if ($cdate) {
    $where[] = 'n.sometable <= 1000';
}

if (empty($where)) {
    $where = '';
} else {
    $where = 'WHERE ' . implode(' AND ', $where);
}

$sql = 'SELECT something, something FROM table1' . $where;