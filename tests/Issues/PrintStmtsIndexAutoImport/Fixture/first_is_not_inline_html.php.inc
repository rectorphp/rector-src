<?php
$a = '';
?>
<div></div>
<?php

require(__DIR__ . '/../../vendor/autoload.php');

$dotenv = \Dotenv\Dotenv::createUnsafeMutable(__DIR__ . '/../../');
$dotenv->load();
-----
<?php
use Dotenv\Dotenv;
$a = '';
?>
<div></div>
<?php

require(__DIR__ . '/../../vendor/autoload.php');

$dotenv = Dotenv::createUnsafeMutable(__DIR__ . '/../../');
$dotenv->load();
